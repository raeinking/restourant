const express = require('express')
const mysql = require('mysql');
const bodyParser = require('body-parser');
const bcrypt = require('bcrypt');
const jwt = require('jsonwebtoken');
require('dotenv').config()
port = process.env.PORT || 2500
const cors = require('cors');

const app = express();
app.use(cors());
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

// Database MySQL connection without the database specified
const con = mysql.createConnection({
    host: process.env.MYSQL_HOST,
    user: process.env.MYSQL_USER,
    password: process.env.MYSQL_PASSWORD,
});

// Connect to MySQL server


// Route to create a new user
app.post('/createuser', async  (req, res) => {
  const { username, email, password, role, database } = req.body;

  // Log the received data
  console.log('Received data:', req.body);

  // Check if all required fields are present
  if (!username || !email || !password || !role || !database) {
      return res.status(400).json({ message: 'All fields are required' });
  }

  try {
    // Hash the password
    const hashedPassword = await bcrypt.hash(password, 10);

    // Create a new connection for each request with the specified database
    const dbCon = mysql.createConnection({
        host: process.env.MYSQL_HOST,
        user: process.env.MYSQL_USER,
        password: process.env.MYSQL_PASSWORD,
        database: database,
    });

    dbCon.connect(function(err) {
        if (err) {
            console.error('Error connecting to the specified database:', err);
            return res.status(500).json({ message: 'Database connection error', error: err });
        }

        // Create the users table if it doesn't exist
        const createTableSql = `
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                password VARCHAR(255) NOT NULL,
                role VARCHAR(255) NOT NULL,
                is_banned BOOLEAN NOT NULL DEFAULT FALSE
            );
        `;

        dbCon.query(createTableSql, (err) => {
            if (err) {
                console.error('Error creating table:', err);
                dbCon.end();
                return res.status(500).json({ message: 'Database error', error: err });
            }

            // Insert user data into the specified database
            const insertUserSql = 'INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)';
            dbCon.query(insertUserSql, [username, email, hashedPassword, role], (err, result) => {
                dbCon.end(); // Close the database connection
                if (err) {
                    console.error('Error inserting data:', err);
                    return res.status(500).json({ message: 'Database error', error: err });
                }
                console.log('User created:', result);
                res.json({ message: 'Registration successful ✅', data: req.body });
            });
        });
    });
} catch (err) {
    console.error('Error hashing password:', err);
    res.status(500).json({ message: 'Server error', error: err });
}
});

//   connect to database
con.connect(function(err) {
  if (err) throw err;
  console.log("Connected to MySQL server!");
});

// Route to handle user login
app.post('/login', (req, res) => {
    const { email, password, database } = req.body;

    if (!email || !password || !database) {
        return res.status(400).json({ message: 'All fields are required' });
    }

    const dbCon = mysql.createConnection({
        host: process.env.MYSQL_HOST,
        user: process.env.MYSQL_USER,
        password: process.env.MYSQL_PASSWORD,
        database: database,
    });

    dbCon.connect(function (err) {
        if (err) {
            console.error('Error connecting to the specified database:', err);
            return res.status(500).json({ message: 'Database connection error', error: err });
        }

        const queryUserSql = 'SELECT * FROM users WHERE email = ?';
        dbCon.query(queryUserSql, [email], (err, results) => {
            dbCon.end();
            if (err) {
                console.error('Error querying data:', err);
                return res.status(500).json({ message: 'Database error', error: err });
            }

            if (results.length === 0) {
                return res.status(400).json({ message: 'User not found' });
            }

            const user = results[0];

            if (user.is_banned) {
                return res.status(403).json({ message: 'User is banned' });
            }

            bcrypt.compare(password, user.password, (err, isMatch) => {
                if (err) {
                    console.error('Error comparing passwords:', err);
                    return res.status(500).json({ message: 'Server error', error: err });
                }

                if (!isMatch) {
                    return res.status(400).json({ message: 'Incorrect password' });
                }

                const token = jwt.sign(
                    { id: user.id, email: user.email, role: user.role },
                    process.env.JWT_SECRET,
                    { expiresIn: '1h' }
                );

                res.status(200).json({
                    message: 'Login successful ✅',
                    token: token,
                    user: {
                        id: user.id,
                        username: user.username,
                        email: user.email,
                        role: user.role,
                    }
                });
            });
        });
    });
});



//   host 
app.listen(port, () => console.log(`Example app listening on port ${port}!`))