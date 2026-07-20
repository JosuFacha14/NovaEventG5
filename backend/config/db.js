//constante para el paquete de mysql
const mysql = require('mysql2');
//creamos la conexion a la base de datos (pool para soportar getConnection)
const mysqlConnection = mysql.createPool({
  host: 'localhost',
  port: 3306,
  user: 'root',
  password: '',
  database: 'BDNovaEventG5',
  multipleStatements: true,
  waitForConnections: true,
  connectionLimit: 10
});
//mensaje que da error si sale mal la conexion a la base de datos o mensaje de exito si se conecta correctamente
mysqlConnection.getConnection((err, connection) => {
  if (!err) {
    console.log('Conexion exitosa');
    connection.release();
    return;
  }
  console.error('Error connecting to MySQL: ' + err.stack);
});

module.exports = mysqlConnection;
