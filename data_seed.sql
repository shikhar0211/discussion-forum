-- Users (password = "password" for all)
INSERT INTO users (username, email, password, role) VALUES
('alice', 'alice@example.com', '$2y$10$e/0Nf4U/0iVQxR7iQm3mHOUN7jZ66m7kqCszK0v3qgDkQ8sZ3dX2K', 'user'),
('bob', 'bob@example.com', '$2y$10$e/0Nf4U/0iVQxR7iQm3mHOUN7jZ66m7kqCszK0v3qgDkQ8sZ3dX2K', 'admin'),
('charlie', 'charlie@example.com', '$2y$10$e/0Nf4U/0iVQxR7iQm3mHOUN7jZ66m7kqCszK0v3qgDkQ8sZ3dX2K', 'user');

-- Topics
INSERT INTO topics (user_id, title, description) VALUES
(1, 'Welcome to the Forum', 'Introduce yourself and get to know the community.'),
(2, 'Best programming resources in 2025', 'Share your favorite courses, books, and channels.'),
(3, 'Show your setup', 'Post your dev environments and gear.');

-- Posts (Replies)
INSERT INTO posts (topic_id, user_id, content) VALUES
(1, 2, 'Hello Alice, welcome to the forum!'),
(1, 3, 'Glad to be here. Looking forward to learning together.'),
(2, 1, 'I recommend the official docs and roadmap.sh.'),
(2, 3, 'Fireship and CS50 are great starting points.'),
(3, 2, 'I use VS Code with a dual-monitor setup.');
