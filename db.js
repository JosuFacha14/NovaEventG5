const mysql = require('mysql2');
const util = require('util');

const mysqlConnection = mysql.createConnection({
  host: 'localhost',
  port: 3306,
  user: 'root',
  password: 'Joel1234',
  database: 'novaevent',   
  multipleStatements: true
});

mysqlConnection.connect((err) => {
  if (!err) {
    console.log('Conexion exitosa');
    return;
  }
  console.error('Error connecting to MySQL: ' + err.stack);
});

// Permite usar await mysqlConnection.queryAsync(...) en los modelos
mysqlConnection.queryAsync = util.promisify(mysqlConnection.query).bind(mysqlConnection);

module.exports = mysqlConnection;