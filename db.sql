SET @@AUTOCOMMIT = 1;

/*

    SQL Creation

*/

DROP DATABASE IF EXISTS Assignment2_Tolkein;
CREATE DATABASE Assignment2_Tolkein;

USE Assignment2_Tolkein;

CREATE TABLE Product(
    productID INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(100),
    summary VARCHAR(100),
    description VARCHAR(5000),
    category VARCHAR(25),
    releaseData TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    price DECIMAL(8,2),
    discount TINYINT,
    PRIMARY KEY (productID)
) AUTO_INCREMENT = 1;

CREATE TABLE ProductImage(
    productID INT NOT NULL,
    src VARCHAR(100) NOT NULL, 
    PRIMARY KEY (productID,src),
    FOREIGN KEY (productID) REFERENCES Product(productID) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE Stock(
    productID INT NOT NULL,
    variant VARCHAR(64) NOT NULL,
    quantity INT DEFAULT 1,
    PRIMARY KEY (productID,variant),
    FOREIGN KEY (productID) REFERENCES Product(productID) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE Account(
    userID INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(256) NOT NULL,
    fName VARCHAR(48) NOT NULL,
    lName VARCHAR(48) NOT NULL,
    phone INT NOT NULL,
    password CHAR(128),
    emailOffers BIT NOT NULL DEFAULT 0,
    createdDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    lastDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (userID)
) AUTO_INCREMENT = 1;

CREATE TABLE OrderRecord(
    orderID INT NOT NULL AUTO_INCREMENT,
    userID INT NOT NULL,
    cost DECIMAL(8,2) NOT NULL,
    paid BIT NOT NULL DEFAULT 0,
    date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (orderID),
    FOREIGN KEY (userID) REFERENCES Account(userID) ON UPDATE CASCADE ON DELETE CASCADE
) AUTO_INCREMENT = 1;

CREATE TABLE OrderProduct (
    orderID INT NOT NULL,
    productID INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    variant VARCHAR(64), 
    PRIMARY KEY (orderID, productID),
    FOREIGN KEY (orderID) REFERENCES OrderRecord(orderID) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (productID) REFERENCES Product(productID) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE ShippingAddress (
    orderID INT NOT NULL,
    street VARCHAR(256) NOT NULL,
    postcode INT NOT NULL,
    suburb VARCHAR(256) NOT NULL,
    state VARCHAR(256) NOT NULL,
    country VARCHAR(256) NOT NULL,
    PRIMARY KEY (orderID),
    FOREIGN KEY (orderID) REFERENCES OrderRecord(orderID) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE Payment (
    orderID INT NOT NULL,
    cardNumber CHAR(128) NOT NULL,
    cvc CHAR(128) NOT NULL,
    expiryDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (orderID),
    FOREIGN KEY (orderID) REFERENCES OrderRecord(orderID) ON UPDATE CASCADE ON DELETE CASCADE
);

/*
    SQL Permissions
*/

CREATE user IF NOT EXISTS dbadmin@localhost;
GRANT all privileges ON Assignment2_Tolkein.* TO dbadmin@localhost;

/*

    SQL Populate

*/

INSERT INTO Product(name,summary,description,category,price,discount) VALUES
('Apple iPhone 11 64GB','All new dual-camera captuers','
A new dual‑camera system captures more of what you see and love. The fastest chip ever in a smartphone and all‑day battery life let you do more and charge less. And the highest‑quality video in a smartphone makes your memories look better than ever.
<ul>
    <li>6.1-inch Liquid Retina HD LCD display || Water and dust resistance (2 metres for up to 30 minutes, IP68)</li>
    <li>Dual-camera system with 12-megapixel Ultra Wide and Wide cameras</li>
    <li>MPN: MWN12CH/A || Model number: A2223 || Storage: 64GB || variant: White</li>
    <li>Shoot 4K video, beautiful portraits and sweeping landscapes with the all-new dual-camera system. Capture your best low-light photo</li>
    <li>Tax invoice available on request || 1 year Manufacturer Warranty</li>
</ul>
','Phone',1749.00,10),
('Apple Watch S3 GPS 38mm','Measure your workouts','
Measure your workouts, from running and cycling to high-intensity interval training. Track and share your daily activity, and get the motivation you need to hit your goals. Better manage everyday stress and monitor your heart rate more effectively. Automatically sync your favourite playlists¹. And stay connected to the people and info you care about most.
<ul>
    <li>GPS and a barometric altimeter track how far and high you go.</li>
    <li>Dual-core processor for faster app performance².</li>
    <li>Ultimate sports watch and intelligent activity tracker.</li>
    <li>Swim-proof so you’re always ready for the pool or ocean³.</li>
    <li>Aluminium case.</li>
    <li>watchOS 4 is even more intuitive and intelligent.</li>
    <li>Apple Watch Series 3 requires an iPhone 6s or later with iOS 13 or later.</li>
</ul>
','Watch',299.99,0),
('Reno 3 Pro','Flagship for 2020','
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum sed velit quis ante maximus posuere tincidunt at arcu. Maecenas accumsan ex id eleifend egestas.
<ul>
    <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
    <li>Vivamus a felis efficitur, bibendum ante quis, aliquam dolor.</li>
    <li>Aliquam tempor enim at leo sagittis, id posuere quam euismod.</li>
    <li>Ut imperdiet velit eu tincidunt lacinia.</li>
    <li>Proin finibus dui a massa dapibus, sit amet suscipit nisl dictum.</li>
</ul>
','Phone',399.99,0),
('SoundTrue around-ear headphones II','Best sound quality around','
Vivamus tristique odio vitae tortor lobortis, molestie euismod lectus porta. Aliquam dapibus maximus orci, vitae porttitor tortor vehicula nec.
<ul>
    <li>Mauris imperdiet diam commodo libero luctus, a consequat risus scelerisque.</li>
    <li>Nulla et velit vitae tellus sagittis tempor.</li>
    <li>Donec laoreet sem luctus, interdum odio non, sagittis orci.</li>
    <li>Morbi nec sem ut ipsum bibendum laoreet.</li>
    <li>Nunc vehicula tortor at elit sagittis fringilla.</li>
</ul>
','Headphone',298.00,0),
('OPPO A91','Find your design','
Curabitur vitae arcu vel ante suscipit sagittis. Suspendisse mattis gravida sollicitudin. Nulla erat urna, euismod id risus eu, pulvinar maximus enim.
<ul>
    <li>Nulla eget odio et magna viverra sagittis.</li>
    <li>Nullam in enim hendrerit, ultrices lectus quis, sodales tortor.</li>
    <li>Donec aliquet ipsum at erat dictum ullamcorper.</li>
    <li>Vivamus id dui interdum, finibus sapien id, gravida ipsum.</li>
    <li>Quisque consectetur nulla non ex porttitor, a fringilla metus placerat.</li>
</ul>
','Phone',479.99,16),
('Samsung Galaxy S8+','Stay connected with everybody','
In quis neque ac libero aliquet posuere id non libero. Vivamus in purus et quam viverra placerat. Maecenas in lacinia odio, sed pellentesque est.
<ul>
    <li>Phasellus quis velit eu ex volutpat pulvinar.</li>
    <li>Nullam auctor metus non consequat egestas.</li>
    <li>Sed fringilla libero non nisl rutrum elementum.</li>
    <li>Nunc sed massa dictum, sollicitudin sapien id, commodo est.</li>
    <li>Nulla feugiat quam eu semper porta.</li>
</ul>
','Phone',359.00,0),
('Suunto 5 All Black','Compact GPS sports watch with great battery life','
Aliquam facilisis ligula eu nulla ultricies, at ultrices felis rutrum. Duis at nisi risus.
<ul>
    <li>Aliquam blandit ex sed eleifend efficitur.</li>
    <li>Aliquam pretium nibh in lorem elementum, non aliquet erat blandit.</li>
    <li>Sed non erat quis arcu ultrices egestas ac eu diam.</li>
    <li>Nullam cursus velit nec neque posuere gravida.</li>
    <li>Nulla congue quam quis eros dignissim, ut lobortis mauris auctor.</li>
</ul>
','Watch',549.99,27),
('Car/Desk Wireless Charging Head','Charge your device up to 2x faster','
Donec ultricies est nec libero porta sagittis. Nullam tempor feugiat gravida. Nunc tempus dolor id ipsum tempus feugiat. In consectetur pharetra enim sed semper.
<ul>
    <li>Vestibulum sodales arcu id nisl euismod pharetra at egestas risus.</li>
    <li>Vestibulum nec erat ac sem sagittis scelerisque.</li>
    <li>Vestibulum tincidunt odio at ante finibus, venenatis hendrerit turpis egestas.</li>
    <li>Maecenas dignissim nulla at consequat pellentesque.</li>
    <li>Nunc non massa sit amet leo suscipit posuere ut id mi.</li>
</ul>
','Charger',59.95,0),
('Razor Recon Chat Xbox','Works with Xbox Series X','
Morbi pretium risus nisl, eu suscipit ipsum blandit et. Nunc efficitur lacinia metus, sit amet consectetur eros laoreet sit amet.
<ul>
    <li>Etiam egestas tellus ultrices enim dignissim maximus.</li>
    <li>Phasellus nec velit quis est gravida placerat eget quis dui.</li>
    <li>Maecenas sit amet purus accumsan nisl ornare maximus.</li>
    <li>Duis auctor nunc sollicitudin turpis mattis, ac tempor velit consequat.</li>
    <li>Nullam finibus sapien nec diam iaculis laoreet.</li>
</ul>
','Headphone',29.99,0),
('Pink Beat Headphones','Hear the music. Not the noise.','
Duis aliquam nunc libero, ac congue odio convallis non. Quisque dapibus lacus quis dolor tempus, at aliquam lectus rutrum.
<ul>
    <li>Vivamus id odio pharetra, commodo lorem sed, cursus nibh.</li>
    <li>Proin dictum quam eget volutpat dapibus.</li>
    <li>Morbi dapibus nibh ac malesuada bibendum.</li>
    <li>Mauris id purus nec est porta bibendum.</li>
    <li>Praesent fermentum augue eget tellus blandit consectetur.</li>
</ul>
','Headphone',320.00,0),
('Spacetalk Kids Watch','Designed for keeping in contact with your child','
Aliquam iaculis elementum finibus. Pellentesque placerat auctor dolor, pellentesque hendrerit augue convallis ac. Maecenas at enim sed dolor cursus gravida et eget massa.
<ul>
    <li>Curabitur viverra ante vel risus sollicitudin placerat.</li>
    <li>Etiam auctor mauris ac ante scelerisque euismod nec in ipsum.</li>
    <li>Nam ullamcorper est quis lacinia porttitor.</li>
    <li>Morbi nec augue at diam malesuada vestibulum.</li>
    <li>Nam tristique nulla id tristique convallis.</li>
</ul>
','Watch',299.99,0),
('Apple MacBook Air 13.3" 1.1GHz 256GB','Retina display with True Tone','
Suspendisse vitae ex et lorem porttitor viverra. Phasellus gravida quam elit, eu volutpat leo pulvinar non.
<ul>
    <li>Quisque bibendum velit vitae ligula rhoncus, nec ornare augue lobortis.</li>
    <li>Quisque eget augue eu lacus volutpat pretium a in massa.</li>
    <li>Vestibulum at arcu at erat rutrum tempor sit amet at nibh.</li>
    <li>Donec accumsan quam nec venenatis accumsan.</li>
    <li>Mauris venenatis lacus vel turpis varius, et tincidunt augue elementum.</li>
</ul>
','Laptop',1549.00,0),
('Garmin Forerunner 45','Easy-to-use running watch','
Phasellus in eleifend diam. Cras efficitur mi quis euismod pulvinar. Donec sed neque faucibus, gravida nisi nec, imperdiet erat.
<ul>
    <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
    <li>Nullam iaculis sem dapibus dolor accumsan porttitor.</li>
    <li>Nunc cursus nibh fringilla tellus tempus scelerisque.</li>
    <li>Cras elementum lorem quis tortor mollis tristique.</li>
    <li>Curabitur mattis dolor et nibh sodales vehicula.</li>
</ul>
','Watch',329.99,0),
('Samsung Tab S3 S Pen','Made for your hand','
Nunc sit amet leo elit. Pellentesque a ex interdum, posuere arcu ut, hendrerit turpis. Nullam feugiat sed lacus quis consectetur. Cras diam diam, aliquam ut enim in, ornare fringilla urna.
<ul>
    <li>Aliquam et tortor dictum, aliquam mauris non, ultricies purus.</li>
    <li>Aenean sit amet risus auctor, tincidunt ex a, gravida massa</li>
    <li>In consectetur massa et quam ornare, sit amet tincidunt velit ultrices</li>
    <li>Nulla ultricies est nec felis hendrerit tincidunt.</li>
    <li>Ut convallis metus eu molestie efficitur.</li>
</ul>
','Stylus',69.00,0),
('IdeaPad 300 Series Laptop','Modern computer with an SSD','
Vivamus diam leo, hendrerit quis malesuada vitae, tempus a augue. In hac habitasse platea dictumst.
<ul>
    <li>Donec egestas sem non nisl sagittis, a maximus nibh fermentum.</li>
    <li>Vestibulum sed est eget ligula elementum fermentum.</li>
    <li>Aenean mollis nibh ac ornare scelerisque.</li>
    <li>Integer congue velit ullamcorper justo mattis, a scelerisque orci molestie.</li>
    <li>Pellentesque ut sapien eget erat venenatis tincidunt at eu est.</li>
</ul>
','Laptop',455.99,0),
('Samsung 9W Fast Wireless Charger Pad','Charger and a statement piece','
Nunc sed orci eget purus euismod consectetur ut nec dolor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae;
<ul>
    <li>Nullam in erat eu lectus feugiat dapibus.</li>
    <li>Nullam porta tellus at mauris tincidunt, et interdum purus feugiat.</li>
    <li>Morbi at turpis vehicula, elementum tellus non, fringilla erat.</li>
    <li>In non tellus sit amet est sodales tincidunt id eu nisl.</li>
    <li>Nam condimentum urna vel arcu laoreet, ac vestibulum tortor finibus.</li>
</ul>
','Charger',29.99,0),
('ViewSonic VB-PEN-002','Best responsiveness','
Vestibulum arcu ex, suscipit vel libero in, facilisis molestie risus. Pellentesque fermentum rhoncus lacinia. Suspendisse ut risus tristique, ornare orci vitae, fringilla arcu.
<ul>
    <li>Suspendisse ornare libero vel dolor sagittis, id sagittis erat congue</li>
    <li>Donec at nunc at diam pulvinar mattis.</li>
    <li>Fusce rhoncus nisl auctor dolor dapibus volutpat.</li>
    <li>Proin tempor dui in felis placerat sollicitudin.</li>
    <li>Mauris euismod dui vitae libero interdum egestas.</li>
</ul>
','Stylus',29.99,0);

INSERT INTO ProductImage(productID,src) VALUES
(1,'phone_1'), (1,'phone_2'), (1,'phone_3'), (1,'phone_4'),
(2,'watch_1'),
(3,'phone_2'),
(4,'headphone_1'),
(5,'phone_3'),
(6,'phone_4'),
(7,'watch_2'),
(8,'charger_1'),
(9,'headphone_2'),
(10,'headphone_3'),
(11,'watch_3'),
(12,'laptop_1'),
(13,'watch_4'),
(14,'stylus_1'),
(15,'laptop_2'),
(16,'charger_2'),
(17,'stylus_2');

INSERT INTO Stock(productID,variant,quantity) VALUES
(1,'Gold',11), (1,'Silver',8), (1,'Space Grey',24), (1,'Midnight Green',15),
(2,'Silver',11),
(3,'Silver',11),
(4,'Silver',11),
(5,'Silver',11),
(6,'Silver',11),
(7,'Silver',11),
(8,'Silver',11),
(9,'Silver',11),
(10,'Silver',11),
(11,'Silver',11),
(12,'Silver',11),
(13,'Silver',11),
(14,'Silver',11),
(15,'Silver',11),
(16,'Silver',11),
(17,'Silver',11);