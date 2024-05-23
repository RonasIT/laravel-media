INSERT INTO users(id, email) VALUES
  (1, 'fidel.kutch@example.com'),
  (2, 'alien.west@example.com');

INSERT INTO media(id, name, owner_id, is_public, link, preview_name, preview_link, meta, created_at, updated_at, deleted_at) VALUES
  (1, 'Product main photo', 1 , true, 'http://localhost/test.jpg', 'test_preview.jpg', 'http://localhost/test_preview.jpg', '{}', '2016-10-20 11:05:00', '2016-10-20 11:05:00', null),
  (2, 'Category Photo photo', 1, false, 'http://localhost/test1.jpg', 'test1_preview.jpg', 'http://localhost/test1_preview.jpg', '{}', '2016-10-20 11:05:00', '2016-10-20 11:05:00', null),
  (3, 'Deleted photo', 2, true, 'http://localhost/test3.jpg', 'test3_preview.jpg', 'http://localhost/test3_preview.jpg', '{}', '2016-10-20 11:05:00', '2016-10-20 11:05:00', '2016-10-20 11:05:00'),
  (4, 'Photo', 2, true, 'http://localhost/test4.jpg', 'test4_preview.jpg', 'http://localhost/test4_preview.jpg', '{}', '2016-10-20 11:05:00', '2016-10-20 11:05:00', null),
  (5, 'Private photo', 2, false, 'http://localhost/test5.jpg', 'test5_preview.jpg', 'http://localhost/test5_preview.jpg', '{}', '2016-10-20 11:05:00', '2016-10-20 11:05:00', null),
  (6, 'Product photo with owner 2', 2, false, 'http://localhost/test6.jpg', 'test6_preview.jpg', 'http://localhost/test6_preview.jpg', '{}', '2016-10-20 11:05:00', '2016-10-20 11:05:00', null);