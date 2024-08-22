#include "WiFi.h"
#include "HTTPClient.h"
#include "Arduino.h"
#include "ArduinoJson.h"
#include "WiFiClientSecure.h"
#include "Adafruit_MQTT.h"
#include "Adafruit_MQTT_Client.h"
#include "AdafruitIO_WiFi.h"

// Wifi router
#define URL "http://192.168.1.15/Web/WEB/database/"
#define WIFI_SSID "HoBa Home CN6_L3"
#define WIFI_PASS "0338440977"
#define LED_PIN LED_BUILTIN
// Adafruit IO
#define IO_USERNAME "tqanh"
#define IO_KEY "aio_ezjj50DheHwfNgHIBXreXR6GTYw3"
AdafruitIO_WiFi io(IO_USERNAME, IO_KEY, WIFI_SSID, WIFI_PASS);
AdafruitIO_Feed *status = io.feed("status");
AdafruitIO_Feed *sensor_data = io.feed("sensor_data");
// Variables for HTTP POST
String postData = ""; //--> Variables sent for HTTP POST request data.
String payload = "";  //--> Variable for receiving response from HTTP POST.
HTTPClient http;      //--> Variable for HTTP Client.
int httpCode;         //--> Variable for HTTP response code.

// Variable for devices data
int state[32] = {0};

// Define Task
void TaskUpdateButton(void *pvParameters);
void TaskCheckTimer(void*pvParameters)

// Take Weather condition data
void sendToData(String data) {
  String temp = data.substring(0, data.indexOf("_"));
  String humid = data.substring(data.indexOf("h") + 1, data.indexOf("_m"));
  String mois = data.substring(data.indexOf("m") + 1, data.indexOf("_l"));
  String light = data.substring(data.indexOf("l") + 1);

  // Print the data to the Serial Monitor
  Serial.println("Temperature: " + String(temp));
  Serial.println("Humidity: " + String(humid));
  Serial.println("Soil moisture: " + String(mois));
  Serial.println("Light intensity: " + String(light));

  // Create JSON object
  StaticJsonDocument<200> jsonDoc;
  jsonDoc["id"] = "esp32";
  jsonDoc["temperature"] = temp;
  jsonDoc["humidity"] = humid;
  jsonDoc["soilMoisture"] = mois;
  jsonDoc["lightIntensity"] = light;

  // Serialize JSON to string
  String jsonString;
  serializeJson(jsonDoc, jsonString);

  // Send JSON data to server
  if (WiFi.status() == WL_CONNECTED) {
    http.begin(URL "updateData.php");
    http.addHeader("Content-Type", "application/json");

    int httpResponseCode = http.POST(jsonString);

    if (httpResponseCode > 0) {
      String response = http.getString();
      Serial.println(httpResponseCode);
      Serial.println(response);
    } else {
      Serial.print("Error on sending POST: ");
      Serial.println(httpResponseCode);
    }

    http.end();
  } else {
    Serial.println("WiFi Disconnected");
  }
}

///////////////////////////////////////////////////// MQTT Functions
void sendMQTTMessage(String message) {
  if (io.status() < AIO_CONNECTED) {
    io.connect();
  }
  // Print the message to the Serial Monitor
  Serial.println("Sending message: " + String(message));

  // Save the message to the status feed
  status->save(message);
}

void getLastMessage(AdafruitIO_Data *data) {
    String message = data->value();
    Serial.println("Last message: " + message);
    sendToData(message);
}
/////////////////////////////////////////////////////

void setup()
{
  Serial.begin(115200);
  pinMode(LED_PIN, OUTPUT);

  // Connect to WiFi
  WiFi.mode(WIFI_STA);
  WiFi.begin(WIFI_SSID, WIFI_PASS);

  int connectingTimeOut = 20; // 20 seconds
  connectingTimeOut = connectingTimeOut * 2;
  while (WiFi.status() != WL_CONNECTED)
  {
    delay(500);
    Serial.print(".");
    if (connectingTimeOut-- == 0)
    {
      Serial.println("Failed to connect to WiFi. Restarting...");
      delay(1000);
      ESP.restart();
      break;
    }
  }
  
  Serial.println();
  Serial.print("Successfully connected to: ");
  Serial.println(WIFI_SSID);
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());
  Serial.println("-------------");

  io.connect();
  sensor_data->onMessage(getLastMessage);
  while (io.status() < AIO_CONNECTED)
  {
    Serial.println("Can't connect to Adafruit IO");
    digitalWrite(LED_PIN, LOW);
    delay(1000);
    digitalWrite(LED_PIN, HIGH);
  }

  // Create tasks
  // xTaskCreate( TaskBlink, "Task Blink" ,2048  ,NULL  ,2 , NULL);
  xTaskCreate( TaskUpdateButton , "Send Http Post", 4096, NULL, 1, NULL);
  xTaskCreate( TaskCheckTimer , "Check Timer", 4096, NULL, 1, NULL);

  delay(5000);
}

void loop()
{
  io.run();
}

void TaskCheckTimer(void *pvParameters) {
  while (true) {
    // Create JSON object
    StaticJsonDocument<200> jsonDoc;
    jsonDoc["id"] = "esp32";

    // Serialize JSON to string
    String jsonString;
    serializeJson(jsonDoc, jsonString);

    if (WiFi.status() == WL_CONNECTED) {
      http.begin(URL "checkTimer.php");
      http.addHeader("Content-Type", "application/json");
      httpCode = http.POST(jsonString);
      if (httpCode > 0) {
        payload = http.getString();
        Serial.println("Response: " + payload);
      } else {
        Serial.println("Error on sending GET: " + String(httpCode));
      }
      http.end();
    } else {
      Serial.println("WiFi not connected");
    }
    vTaskDelay(30000 / portTICK_PERIOD_MS); // Delay for 30 seconds
  }
}

void TaskUpdateButton(void *pvParameters) {

  while (true) {
    if (WiFi.status() == WL_CONNECTED) {
      http.begin(URL "getButtonState.php");

      // Create JSON object
      StaticJsonDocument<200> jsonDoc;
      jsonDoc["relay"] = state[0];
      String jsonData;
      serializeJson(jsonDoc, jsonData);

      // Set headers
      http.addHeader("Content-Type", "application/json");

      // Send POST request
      httpCode = http.POST(jsonData);

      // Check the response
      if (httpCode > 0) {
        payload = http.getString();
        Serial.println("Response: " +  payload);

        int start = 0;
        int end = payload.indexOf("<br>", start);
        while (end != -1) {
          String line = payload.substring(start, end);
          int separatorIndex = line.indexOf(" - Button State: ");
          if (separatorIndex != -1) {
            String idStr = line.substring(4, separatorIndex); // Extract ID
            String stateStr = line.substring(separatorIndex + 17); // Extract button state

            int relayIndex = idStr.toInt() - 1; // Convert ID to relay index (assuming IDs start from 1)
            int status = (stateStr == "ON") ? 1 : 0;

            if (relayIndex >= 0 && relayIndex < 32 && status != state[relayIndex]) {
              state[relayIndex] = status;

              String mqttMess = "!RELAY" + String(relayIndex) + (status == 1 ? ":ON#" : ":OFF#");  
              if (status == 1) {
                Serial.println("Relay " + String(relayIndex + 1) + " turned ON");
              } else {
                Serial.println("Relay " + String(relayIndex + 1) + " turned OFF");
              }
              sendMQTTMessage(mqttMess.c_str());
            }
          }
          start = end + 4; // Move past the "<br>"
          end = payload.indexOf("<br>", start);
        }
      } else {
        Serial.println("Error on sending POST: " + String(httpCode));
      }
      http.end();
    } else {
      Serial.println("WiFi not connected");
    }
    vTaskDelay(5000 / portTICK_PERIOD_MS); // Delay for 10 seconds
  }
}