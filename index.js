//constante para el paquete de mysql
const mysql = require('mysql');
//constante para el paquete de express
const express = require('express');
const app = express();
//constante para el paquete de body-parser
const bp = require('body-parser');

app.use(bp.json());

const mysqlConnection = mysql.createConnection({
  host: 'localhost',
  port: 3306,
  user: 'root',
  password: '',
  database: 'BDNovaEventG5',
  multipleStatements: true
});

mysqlConnection.connect((err) => {
  if (!err) {
    console.log('Conexion exitosa');
    return;
  }
  console.error('Error connecting to MySQL: ' + err.stack);
  
});

//ejecutar en puerto especifico
app.listen(3000, () => {
  console.log('Server is running on port 3000');
});  