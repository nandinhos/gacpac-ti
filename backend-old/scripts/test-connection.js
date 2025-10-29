#!/usr/bin/env node

/**
 * SGAITI-UM Database Connection Test
 *
 * Simple test to verify database connection and basic operations
 */

require('dotenv').config();
const mysql = require('mysql2/promise');

async function testConnection() {
  console.log('Testing database connection...');

  try {
    const connection = await mysql.createConnection({
      host: process.env.DB_HOST || 'localhost',
      port: process.env.DB_PORT || 3306,
      user: 'sgaiti_user',
      password: 'sgaiti_pass',
      database: process.env.DB_NAME || 'sgaiti_db'
    });

    console.log('✓ Connected to database successfully');

    // Test basic query
    const [rows] = await connection.execute('SELECT 1 as test');
    console.log('✓ Basic query working:', rows[0].test);

    // Test database exists
    const [databases] = await connection.execute('SHOW DATABASES');
    const dbExists = databases.some(db => db.Database === 'sgaiti_db');
    console.log('✓ Database exists:', dbExists);

    // Test table exists
    const [tables] = await connection.execute('SHOW TABLES');
    const hasUsersTable = tables.some(table => table['Tables_in_sgaiti_db'] === 'users');
    console.log('✓ Users table exists:', hasUsersTable);

    // Test user creation
    console.log('\n--- Testing User Creation ---');
    const { v4: uuidv4 } = require('uuid');
    const userId = uuidv4();

    const [result] = await connection.execute(
      'INSERT INTO users (id, name, rank, military_id, is_active) VALUES (?, ?, ?, ?, ?)',
      [userId, 'Test User', 'Capitão', '999.999.999-99', true]
    );

    if (result.affectedRows > 0) {
      console.log('✓ User created successfully');

      // Test user retrieval
      const [users] = await connection.execute('SELECT * FROM users WHERE id = ?', [userId]);
      if (users.length > 0) {
        console.log('✓ User retrieved successfully');
        console.log(`  - Name: ${users[0].name}`);
        console.log(`  - Rank: ${users[0].rank}`);
        console.log(`  - Military ID: ${users[0].military_id}`);

        // Clean up
        await connection.execute('DELETE FROM users WHERE id = ?', [userId]);
        console.log('✓ Test user deleted');
      } else {
        console.log('✗ User not found after creation');
      }
    } else {
      console.log('✗ User creation failed');
    }

    await connection.end();
    console.log('✓ Connection closed');
    console.log('\n🎉 Database is working correctly!');
    return true;

  } catch (error) {
    console.error('✗ Database connection failed:', error.message);
    console.error('Please check your database configuration in .env file');
    return false;
  }
}

// Run the test
testConnection().then(success => {
  process.exit(success ? 0 : 1);
});