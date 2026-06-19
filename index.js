//constante para el paquete de mysql
const mysql = require('mysql');
//constante para el paquete de express
const express = require('express');
const app = express();
//constante para el paquete de body-parser
const bp = require('body-parser');
//constante para la conexión a la base de datos
const db = require('./config/db');
app.use(bp.json());

app.listen(3000, () => {
  console.log('Server is running on port 3000');
});
