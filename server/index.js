const express = require('express')
const mysql = require('mysql');
const bodyParser = require('body-parser');
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
app.post('/createuser', (req, res) => {
  const { username, email, password, role, database } = req.body;

  // Log the received data
  console.log('Received data:', req.body);

  // Check if all required fields are present
  if (!username || !email || !password || !role || !database) {
      return res.status(400).json({ message: 'All fields are required' });
  }

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
              role VARCHAR(255) NOT NULL
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
          dbCon.query(insertUserSql, [username, email, password, role], (err, result) => {
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
});

//   connect to database
con.connect(function(err) {
  if (err) throw err;
  console.log("Connected to MySQL server!");
});

//   host 
app.listen(port, () => console.log(`Example app listening on port ${port}!`))