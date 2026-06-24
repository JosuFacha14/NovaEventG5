//constantes para el paquete mysql, express, body-parser y la conexion a la base de datos
const mysql = require('mysql');
const express = require('express');
const app = express();
const bp = require('body-parser');
const db = require('./config/db');

app.use(bp.json());

// rutas del modulo de reservacion
const reservacionRoutes = require('./routes/reservacionRoutes');

// prefijo de las rutas del modulo re
app.use('/api/re', reservacionRoutes);
//prefijo de las rutas del modulo de ventas
app.use(
    '/api',
    require('./routes/ventasRoutes')
);

app.use(
    '/api',
    require('./routes/personasRoutes')
);

// rutas del modulo de inventario
const inventarioRoutes = require('./routes/inventarioRoutes');
app.use('/api/in', inventarioRoutes);

app.listen(3000, () => {
    console.log('Server is running on port 3000');
});