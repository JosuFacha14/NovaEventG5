//constante para el paquete de mysql
const mysql = require('mysql');
//creamos la conexion a la base de datos
const mysqlConnection = mysql.createConnection({
  host: 'localhost',
  port: 3306,
  user: 'root',
  password: '',
  database: 'BDNovaEventG5',
  multipleStatements: true
});
//mensaje que da error si sale mal la conexion a la base de datos o mensaje de exito si se conecta correctamente
mysqlConnection.connect((err) => {
  if (!err) {
    console.log('Conexion exitosa');
    return;
  }
  console.error('Error connecting to MySQL: ' + err.stack);
});

module.exports = mysqlConnection;