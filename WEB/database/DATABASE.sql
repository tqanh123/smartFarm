
CREATE TABLE `leds_status` (
    `id` integer NOT NULL,
    `led_state` varchar(255) NOT NULL,
    `time` time NOT NULL,
    `date` date NOT NULL,
    PRIMARY KEY (`id`)
)

INSERT INTO `leds_status`(`id`, `status`, `time`, `date`) VALUES ('esp32','ON',NOW(),NOW())

CREATE TABLE `buttons_status` (
    `id` integer NOT NULL,
    `button_state` varchar(255) NOT NULL,
    `time` time NOT NULL,
    `date` date NOT NULL,
    PRIMARY KEY (`id`)
)

INSERT INTO `buttons_status` (`id`, `button_name`, `button_state`, `time`, `date`)
VALUES 
('1', 'Name 1', 'OFF', NOW(), NOW()),
('2', 'Name 2', 'OFF', NOW(), NOW()),
('3', 'Name 3', 'OFF', NOW(), NOW()),
('4', 'Name 4', 'OFF', NOW(), NOW()),
('5', 'Name 5', 'OFF', NOW(), NOW()),
('6', 'Name 6', 'OFF', NOW(), NOW()),
('7', 'Name 7', 'OFF', NOW(), NOW()),
('8', 'Name 8', 'OFF', NOW(), NOW()),
('9', 'Name 9', 'OFF', NOW(), NOW()),
('10', 'Name 10', 'OFF', NOW(), NOW()),
('11', 'Name 11', 'OFF', NOW(), NOW()),
('12', 'Name 12', 'OFF', NOW(), NOW()),
('13', 'Name 13', 'OFF', NOW(), NOW()),
('14', 'Name 14', 'OFF', NOW(), NOW()),
('15', 'Name 15', 'OFF', NOW(), NOW()),
('16', 'Name 16', 'OFF', NOW(), NOW()),
('17', 'Name 17', 'OFF', NOW(), NOW()),
('18', 'Name 18', 'OFF', NOW(), NOW()),
('19', 'Name 19', 'OFF', NOW(), NOW()),
('20', 'Name 20', 'OFF', NOW(), NOW()),
('21', 'Name 21', 'OFF', NOW(), NOW()),
('22', 'Name 22', 'OFF', NOW(), NOW()),
('23', 'Name 23', 'OFF', NOW(), NOW()),
('24', 'Name 24', 'OFF', NOW(), NOW()),
('25', 'Name 25', 'OFF', NOW(), NOW()),
('26', 'Name 26', 'OFF', NOW(), NOW()),
('27', 'Name 27', 'OFF', NOW(), NOW()),
('28', 'Name 28', 'OFF', NOW(), NOW()),
('29', 'Name 29', 'OFF', NOW(), NOW()),
('30', 'Name 30', 'OFF', NOW(), NOW()),
('31', 'Name 31', 'OFF', NOW(), NOW()),
('32', 'Name 32', 'OFF', NOW(), NOW());

CREATE TABLE `weather_conditions` (
    `id` varchar(255) NOT NULL,
    `Temperature` varchar(255) NOT NULL,
    `Humidity` varchar(255) NOT NULL,
    `light` varchar(255) NOT NULL,
    `soil_moisture` varchar(255) NOT NULL,
    `time` time NOT NULL,
    `date` date NOT NULL,
    PRIMARY KEY (`id`)
);

INSERT INTO `weather_conditions` (`id`, `Temperature`, `Humidity`, `light`, `soil_moisture`, `time`, `date`) 
VALUES ('esp32', '25', '60', '300', '40', NOW(), NOW());

CREATE TABLE `Timer` (
    `id` int NOT NULL auto_increment,
    `timer_name` varchar(255) NOT NULL,
    `timer_state` varchar(255) NOT NULL,
    `state_update` varchar(255) NOT NULL,
    `time_update` time NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `T-B` (
    `id` int NOT NULL auto_increment,
    `timer_id` INT NOT NULL,
    `button_id` INT NOT NULL,
    PRIMARY KEY (`id`),
    Foreign Key (`timer_id`) REFERENCES `Timer`(`id`),
    Foreign Key (`button_id`) REFERENCES `buttons_status`(`id`)
);