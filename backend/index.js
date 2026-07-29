//constantes para el paquete mysql, express, body-parser y la conexion a la base de datos
const mysql = require('mysql2');
const express = require('express');
const app = express();
const bp = require('body-parser');
const db = require('./config/db');

app.use(bp.json());

// rutas del modulo de reservacion y prefijo de las rutas del modulo RE
const reservacionRoutes = require('./routes/reservacionRoutes');
app.use('/api/re', reservacionRoutes);

//prefijo de las rutas del modulo de ventas
app.use(
    '/api',
    require('./routes/ventasRoutes')
);

//prefijo de las rutas del modulo de personas
app.use(
    '/api',
    require('./routes/personasRoutes')
);

// rutas del modulo de inventario
const inventarioRoutes = require('./routes/inventarioRoutes');
app.use('/api/in', inventarioRoutes);

// rutas del modulo de reportes
const reportesRoutes = require('./routes/reportesRoutes');
app.use('/api', reportesRoutes);

//rutas para el login, registro y validación de nombre de usuario
const authRoutes = require('./routes/authRoutes');
app.use('/api/auth', authRoutes);

//mensaje que muestra si el servidor esta activo en el puerto 3000
app.listen(3000, () => {
    console.log('Server is running on port 3000');
});