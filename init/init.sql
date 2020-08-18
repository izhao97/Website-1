/* TODO: create tables */

CREATE TABLE 'images' (
	'id'	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	'images_name'	TEXT NOT NULL,
	'images_ext'	TEXT NOT NULL,
	'uploader_name' TEXT
);

INSERT INTO images(images_name, images_ext) VALUES ('Sunset', 'jpeg');
-- image obtained from: https://images.pexels.com/photos/129458/pexels-photo-129458.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940

INSERT INTO images(images_name, images_ext) VALUES ('Waves', 'jpg');
-- image obtained from: https://static1.squarespace.com/static/552b1764e4b03cff36ace4b2/t/5a84e67653450a64135c4a74/1518659212478/2018_February12.jpg?format=2500w

INSERT INTO images(images_name, images_ext ) VALUES ('River Journey', 'jpg');
-- image obtained from: https://static1.squarespace.com/static/552b1764e4b03cff36ace4b2/t/5ac01f69aa4a998a3b755233/1522540421935/2018_April5.jpg?format=2500w

INSERT INTO images(images_name, images_ext ) VALUES ('Progress Quote', 'jpg');
-- image obtained from: https://static1.squarespace.com/static/552b1764e4b03cff36ace4b2/t/5aa9bab1e4966b47161d6cf2/1521072844015/2018_March12.jpg?format=2500w

INSERT INTO images(images_name, images_ext ) VALUES ('Mountains', 'jpeg');
-- image obtained from: https://www.pexels.com/photo/adventure-clouds-conifer-countryside-629167/

INSERT INTO images(images_name, images_ext ) VALUES ('Kind Quote', 'jpg');
-- image obtained from : https://static1.squarespace.com/static/552b1764e4b03cff36ace4b2/t/5a84e63b41920237fe7755b3/1518659143531/2018_February9.jpg?format=2500w

INSERT INTO images(images_name, images_ext ) VALUES ('Bold Quote', 'jpg');
-- image obtained from: https://static1.squarespace.com/static/552b1764e4b03cff36ace4b2/t/5aa9ba8b24a694c63a79615d/1521072810502/2018_March11.jpg?format=2500w

INSERT INTO images(images_name, images_ext ) VALUES ('Splash', 'jpeg');
-- image obtained from: https://www.pexels.com/photo/background-bokeh-bubble-christmas-534736/

INSERT INTO images(images_name, images_ext ) VALUES ('Stars', 'jpg');
-- image obtained from: https://www.pexels.com/photo/night-stars-sky-7477/

INSERT INTO images(images_name, images_ext ) VALUES ('Forest', 'jpeg');
-- image obtained from: https://www.pexels.com/photo/street-tree-nature-wallpaper-52599/


CREATE TABLE 'tags' (
	'id'	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	'tags_name'	TEXT NOT NULL
);

INSERT INTO tags (tags_name) VALUES ('Nature');
INSERT INTO tags (tags_name) VALUES ('Quote');
INSERT INTO tags (tags_name) VALUES ('Blue');
INSERT INTO tags (tags_name) VALUES ('Water');
INSERT INTO tags (tags_name) VALUES ('Calendar');


CREATE TABLE 'accounts' (
	'id'	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	'username'	TEXT NOT NULL,
	'password'	TEXT NOT NULL,
  'session' TEXT
);

INSERT INTO accounts (username, password, session) VALUES ('Iris', "$2y$10$IK0HmIpVESmG1oaBlgt.b.qBpxyvyaafO8Iqq98aWp4Mbb5CkpZ8K",  NULL);
-- password: penguin
INSERT INTO accounts (username, password, session) VALUES ('Tester', "$2y$10$YMV6Sy34EqLoVON8RhwlBusVtMcXmcOuCTLwPKUUEE/ISDy.gIxQy", NULL);
-- password: puppy
CREATE TABLE 'imagetag'(
	'id' INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	'image_id' INTEGER,
	'tag_id' INTEGER
);

INSERT INTO imagetag (image_id, tag_id) VALUES ('1', '1');
INSERT INTO imagetag (image_id, tag_id) VALUES ('1', '4');
INSERT INTO imagetag (image_id, tag_id) VALUES ('2', '1');
INSERT INTO imagetag (image_id, tag_id) VALUES ('2', '3');
INSERT INTO imagetag (image_id, tag_id) VALUES ('2', '4');
INSERT INTO imagetag (image_id, tag_id) VALUES ('3', '1');
INSERT INTO imagetag (image_id, tag_id) VALUES ('3', '5');
INSERT INTO imagetag (image_id, tag_id) VALUES ('3', '4');
INSERT INTO imagetag (image_id, tag_id) VALUES ('4', '2');
INSERT INTO imagetag (image_id, tag_id) VALUES ('5', '1');
INSERT INTO imagetag (image_id, tag_id) VALUES ('5', '4');
INSERT INTO imagetag (image_id, tag_id) VALUES ('6', '2');
INSERT INTO imagetag (image_id, tag_id) VALUES ('7', '2');
INSERT INTO imagetag (image_id, tag_id) VALUES ('8', '3');
INSERT INTO imagetag (image_id, tag_id) VALUES ('8', '4');
INSERT INTO imagetag (image_id, tag_id) VALUES ('10', '1');
