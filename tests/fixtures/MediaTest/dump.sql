INSERT INTO users(id, email) VALUES
  (1, 'fidel.kutch@example.com'),
  (2, 'alien.west@example.com');

INSERT INTO media(id, name, owner_id, is_public, link, preview_id, meta, created_at, updated_at) VALUES
  (1, 'preview_Product main photo', 1 , true, 'http://localhost/test_preview_1.jpg', null, '{}', '2016-10-20 11:05:00', '2016-10-20 11:05:00'),
  (2, 'preview_Category Photo photo', 1, false, 'http://localhost/test_preview_2.jpg', null, '{}', '2016-10-20 11:05:00', '2016-10-20 11:05:00'),
  (3, 'preview_Photo', 2, true, 'http://localhost/test_preview_4.jpg', null, '{}', '2016-10-20 11:05:00', '2016-10-20 11:05:00'),
  (4, 'preview_Private photo', 2, false, 'http://localhost/test_preview_5.jpg', null, '{}', '2016-10-20 11:05:00', '2016-10-20 11:05:00'),
  (5, 'preview_Product photo with owner 2', 2, false, 'http://localhost/test_preview_6.jpg', null, '{}', '2016-10-20 11:05:00', '2016-10-20 11:05:00'),
  (6, 'Product main photo', 1 , true, 'http://localhost/test.jpg', 1, '{}', '2016-10-20 11:05:00', '2016-10-20 11:05:00'),
  (7, 'Category Photo photo', 1, false, 'http://localhost/test1.jpg', 2, '{}', '2016-10-20 11:05:00', '2016-10-20 11:05:00'),
  (8, 'Photo', 2, true, 'http://localhost/test4.jpg', 3, '{}', '2016-10-20 11:05:00', '2016-10-20 11:05:00'),
  (9, 'Private photo', 2, false, 'http://localhost/test5.jpg', 4, '{}', '2016-10-20 11:05:00', '2016-10-20 11:05:00'),
  (10, 'Product photo with owner 2', 2, false, 'http://localhost/test6.jpg', 5, '{}', '2016-10-20 11:05:00', '2016-10-20 11:05:00');