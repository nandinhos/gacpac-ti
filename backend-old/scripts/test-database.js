#!/usr/bin/env node

/**
 * SGAITI-UM Database Test Suite
 *
 * This script tests all CRUD operations for the database
 * to ensure everything is working correctly.
 */

require('dotenv').config();
const mysql = require('mysql2/promise');
const { v4: uuidv4 } = require('uuid');

// Database connection
const db = mysql.createConnection({
  host: process.env.DB_HOST || 'localhost',
  port: process.env.DB_PORT || 33060,
  user: process.env.DB_USER || 'sgaiti_user',
  password: process.env.DB_PASSWORD || 'sgaiti_pass',
  database: process.env.DB_NAME || 'sgaiti_db'
});

async function testUsersCRUD() {
  console.log('\n=== Testing Users CRUD Operations ===');

  try {
    // Test 1: Create user
    console.log('\n1. Testing user creation...');
    const userId = uuidv4();
    const createQuery = `
      INSERT INTO users (id, name, rank, military_id, sector_id, is_active)
      VALUES (?, ?, ?, ?, ?, ?)
    `;
    const [createResult] = await db.query(createQuery, [
      userId,
      'Test User',
      'Capitão Aviador',
      '999.999.999-99',
      null, // No sector for test
      true
    ]);

    if (createResult.affectedRows === 1) {
      console.log('✓ User created successfully');
    } else {
      console.log('✗ User creation failed');
      return false;
    }

    // Test 2: Read user
    console.log('\n2. Testing user retrieval...');
    const [users] = await db.query('SELECT * FROM users WHERE id = ?', [userId]);
    if (users.length > 0) {
      console.log('✓ User retrieved successfully');
      console.log(`  - Name: ${users[0].name}`);
      console.log(`  - Rank: ${users[0].rank}`);
      console.log(`  - Military ID: ${users[0].military_id}`);
    } else {
      console.log('✗ User retrieval failed');
      return false;
    }

    // Test 3: Update user
    console.log('\n3. Testing user update...');
    const updateQuery = `
      UPDATE users
      SET name = ?, rank = ?, is_active = ?
      WHERE id = ?
    `;
    const [updateResult] = await db.query(updateQuery, [
      'Updated Test User',
      'Major Aviador',
      false,
      userId
    ]);

    if (updateResult.affectedRows === 1) {
      console.log('✓ User updated successfully');
    } else {
      console.log('✗ User update failed');
      return false;
    }

    // Verify update
    const [updatedUsers] = await db.query('SELECT * FROM users WHERE id = ?', [userId]);
    if (updatedUsers.length > 0 && updatedUsers[0].name === 'Updated Test User') {
      console.log('✓ User update verified');
    } else {
      console.log('✗ User update verification failed');
      return false;
    }

    // Test 4: Delete user
    console.log('\n4. Testing user deletion...');
    const [deleteResult] = await db.query('DELETE FROM users WHERE id = ?', [userId]);

    if (deleteResult.affectedRows === 1) {
      console.log('✓ User deleted successfully');
    } else {
      console.log('✗ User deletion failed');
      return false;
    }

    // Verify deletion
    const [deletedUsers] = await db.query('SELECT * FROM users WHERE id = ?', [userId]);
    if (deletedUsers.length === 0) {
      console.log('✓ User deletion verified');
    } else {
      console.log('✗ User deletion verification failed');
      return false;
    }

    return true;

  } catch (error) {
    console.error('✗ Error testing users CRUD:', error.message);
    return false;
  }
}

async function testSectorsCRUD() {
  console.log('\n=== Testing Sectors CRUD Operations ===');

  try {
    // Test 1: Create sector
    console.log('\n1. Testing sector creation...');
    const sectorId = uuidv4();
    const [createResult] = await db.query(
      'INSERT INTO sectors (id, name, description) VALUES (?, ?, ?)',
      [sectorId, 'Test Sector', 'Test sector for database testing']
    );

    if (createResult.affectedRows === 1) {
      console.log('✓ Sector created successfully');
    } else {
      console.log('✗ Sector creation failed');
      return false;
    }

    // Test 2: Read sector
    console.log('\n2. Testing sector retrieval...');
    const [sectors] = await db.query('SELECT * FROM sectors WHERE id = ?', [sectorId]);
    if (sectors.length > 0) {
      console.log('✓ Sector retrieved successfully');
      console.log(`  - Name: ${sectors[0].name}`);
      console.log(`  - Description: ${sectors[0].description}`);
    } else {
      console.log('✗ Sector retrieval failed');
      return false;
    }

    // Test 3: Update sector
    console.log('\n3. Testing sector update...');
    const [updateResult] = await db.query(
      'UPDATE sectors SET name = ?, description = ? WHERE id = ?',
      ['Updated Test Sector', 'Updated description', sectorId]
    );

    if (updateResult.affectedRows === 1) {
      console.log('✓ Sector updated successfully');
    } else {
      console.log('✗ Sector update failed');
      return false;
    }

    // Test 4: Delete sector
    console.log('\n4. Testing sector deletion...');
    const [deleteResult] = await db.query('DELETE FROM sectors WHERE id = ?', [sectorId]);

    if (deleteResult.affectedRows === 1) {
      console.log('✓ Sector deleted successfully');
    } else {
      console.log('✗ Sector deletion failed');
      return false;
    }

    return true;

  } catch (error) {
    console.error('✗ Error testing sectors CRUD:', error.message);
    return false;
  }
}

async function testAssetsCRUD() {
  console.log('\n=== Testing Assets CRUD Operations ===');

  try {
    // Test 1: Create asset
    console.log('\n1. Testing asset creation...');
    const assetId = uuidv4();
    const [createResult] = await db.query(
      `INSERT INTO assets (
        id, qr_code, name, category, serial_number, status, acquisition_date
      ) VALUES (?, ?, ?, ?, ?, ?, ?)`,
      [
        assetId,
        'SGAITI-9999',
        'Test Asset',
        'Computação',
        'TEST-SN-001',
        'Disponível',
        '2024-01-01'
      ]
    );

    if (createResult.affectedRows === 1) {
      console.log('✓ Asset created successfully');
    } else {
      console.log('✗ Asset creation failed');
      return false;
    }

    // Test 2: Read asset
    console.log('\n2. Testing asset retrieval...');
    const [assets] = await db.query('SELECT * FROM assets WHERE id = ?', [assetId]);
    if (assets.length > 0) {
      console.log('✓ Asset retrieved successfully');
      console.log(`  - QR Code: ${assets[0].qr_code}`);
      console.log(`  - Name: ${assets[0].name}`);
      console.log(`  - Category: ${assets[0].category}`);
    } else {
      console.log('✗ Asset retrieval failed');
      return false;
    }

    // Test 3: Update asset
    console.log('\n3. Testing asset update...');
    const [updateResult] = await db.query(
      'UPDATE assets SET name = ?, status = ? WHERE id = ?',
      ['Updated Test Asset', 'Em Uso', assetId]
    );

    if (updateResult.affectedRows === 1) {
      console.log('✓ Asset updated successfully');
    } else {
      console.log('✗ Asset update failed');
      return false;
    }

    // Test 4: Delete asset
    console.log('\n4. Testing asset deletion...');
    const [deleteResult] = await db.query('DELETE FROM assets WHERE id = ?', [assetId]);

    if (deleteResult.affectedRows === 1) {
      console.log('✓ Asset deleted successfully');
    } else {
      console.log('✗ Asset deletion failed');
      return false;
    }

    return true;

  } catch (error) {
    console.error('✗ Error testing assets CRUD:', error.message);
    return false;
  }
}

async function testRelationships() {
  console.log('\n=== Testing Database Relationships ===');

  try {
    // Test 1: Create sector and user
    console.log('\n1. Testing sector-user relationship...');
    const sectorId = uuidv4();
    const userId = uuidv4();

    await db.query('INSERT INTO sectors (id, name, description) VALUES (?, ?, ?)',
      [sectorId, 'Relationship Test Sector', 'Test sector for relationship testing']);

    await db.query('INSERT INTO users (id, name, rank, military_id, sector_id, is_active) VALUES (?, ?, ?, ?, ?, ?)',
      [userId, 'Relationship Test User', 'Capitão', '999.999.999-98', sectorId, true]);

    // Verify relationship
    const [users] = await db.query('SELECT u.name, s.name as sector_name FROM users u LEFT JOIN sectors s ON u.sector_id = s.id WHERE u.id = ?', [userId]);

    if (users.length > 0 && users[0].sector_name === 'Relationship Test Sector') {
      console.log('✓ Sector-user relationship working correctly');
    } else {
      console.log('✗ Sector-user relationship failed');
      return false;
    }

    // Test 2: Create asset with user custody
    console.log('\n2. Testing asset-custody relationship...');
    const assetId = uuidv4();

    await db.query(
      `INSERT INTO assets (id, qr_code, name, category, status, custodian_user_id)
       VALUES (?, ?, ?, ?, ?, ?)`,
      [assetId, 'SGAITI-9998', 'Relationship Test Asset', 'Computação', 'Em Uso', userId]
    );

    const [assets] = await db.query('SELECT a.name, u.name as custodian_name FROM assets a LEFT JOIN users u ON a.custodian_user_id = u.id WHERE a.id = ?', [assetId]);

    if (assets.length > 0 && assets[0].custodian_name === 'Relationship Test User') {
      console.log('✓ Asset-custody relationship working correctly');
    } else {
      console.log('✗ Asset-custody relationship failed');
      return false;
    }

    // Cleanup
    await db.query('DELETE FROM assets WHERE id = ?', [assetId]);
    await db.query('DELETE FROM users WHERE id = ?', [userId]);
    await db.query('DELETE FROM sectors WHERE id = ?', [sectorId]);

    return true;

  } catch (error) {
    console.error('✗ Error testing relationships:', error.message);
    return false;
  }
}

async function testConstraints() {
  console.log('\n=== Testing Database Constraints ===');

  try {
    // Test 1: Unique constraint on military_id
    console.log('\n1. Testing unique military_id constraint...');
    const userId1 = uuidv4();
    const userId2 = uuidv4();

    await db.query('INSERT INTO users (id, name, rank, military_id, is_active) VALUES (?, ?, ?, ?, ?)',
      [userId1, 'First User', 'Capitão', '999.999.999-97', true]);

    try {
      await db.query('INSERT INTO users (id, name, rank, military_id, is_active) VALUES (?, ?, ?, ?, ?)',
        [userId2, 'Second User', 'Capitão', '999.999.999-97', true]);
      console.log('✗ Unique constraint on military_id not working');
      return false;
    } catch (error) {
      if (error.code === 'ER_DUP_ENTRY') {
        console.log('✓ Unique constraint on military_id working correctly');
      } else {
        console.log('✗ Unexpected error:', error.message);
        return false;
      }
    }

    // Test 2: Unique constraint on qr_code
    console.log('\n2. Testing unique qr_code constraint...');
    const assetId1 = uuidv4();
    const assetId2 = uuidv4();

    await db.query(
      `INSERT INTO assets (id, qr_code, name, category, status)
       VALUES (?, ?, ?, ?, ?)`,
      [assetId1, 'SGAITI-9997', 'First Asset', 'Computação', 'Disponível']
    );

    try {
      await db.query(
        `INSERT INTO assets (id, qr_code, name, category, status)
         VALUES (?, ?, ?, ?, ?)`,
        [assetId2, 'SGAITI-9997', 'Second Asset', 'Computação', 'Disponível']
      );
      console.log('✗ Unique constraint on qr_code not working');
      return false;
    } catch (error) {
      if (error.code === 'ER_DUP_ENTRY') {
        console.log('✓ Unique constraint on qr_code working correctly');
      } else {
        console.log('✗ Unexpected error:', error.message);
        return false;
      }
    }

    // Cleanup
    await db.query('DELETE FROM assets WHERE id = ?', [assetId1]);
    await db.query('DELETE FROM users WHERE id = ?', [userId1]);

    return true;

  } catch (error) {
    console.error('✗ Error testing constraints:', error.message);
    return false;
  }
}

async function runAllTests() {
  console.log('🚀 Starting SGAITI-UM Database Test Suite');
  console.log('==========================================');

  let passedTests = 0;
  let totalTests = 0;

  // Test Users CRUD
  totalTests++;
  if (await testUsersCRUD()) {
    passedTests++;
  }

  // Test Sectors CRUD
  totalTests++;
  if (await testSectorsCRUD()) {
    passedTests++;
  }

  // Test Assets CRUD
  totalTests++;
  if (await testAssetsCRUD()) {
    passedTests++;
  }

  // Test Relationships
  totalTests++;
  if (await testRelationships()) {
    passedTests++;
  }

  // Test Constraints
  totalTests++;
  if (await testConstraints()) {
    passedTests++;
  }

  console.log('\n==========================================');
  console.log(`📊 Test Results: ${passedTests}/${totalTests} tests passed`);

  if (passedTests === totalTests) {
    console.log('🎉 All tests passed! Database is working correctly.');
    return true;
  } else {
    console.log('❌ Some tests failed. Please check the database configuration.');
    return false;
  }
}

async function main() {
  try {
    console.log('Connecting to database...');
    await db.connect();
    console.log('✓ Connected to database');

    const success = await runAllTests();

    await db.end();
    console.log('✓ Database connection closed');

    process.exit(success ? 0 : 1);

  } catch (error) {
    console.error('✗ Failed to connect to database:', error.message);
    console.error('Please check your database configuration in .env file');
    process.exit(1);
  }
}

// Run the tests
main();