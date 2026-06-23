<<<<<<< Updated upstream
//constantes para el paquete mysql, express, body-parser y la conexion a la base de datos
const mysql = require('mysql');
=======
//constante para el paquete de express
>>>>>>> Stashed changes
const express = require('express');
const app = express();
const bp = require('body-parser');
const db = require('./config/db');

app.use(bp.json());

<<<<<<< Updated upstream
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
=======
// Conexion a MySQL (misma configuracion que tenias, ahora vive en db.js)
require('./db');

// Rutas del modulo de inventario
const inventarioRoutes = require('./routes/INindex');
app.use('/api/inventario', inventarioRoutes);

// Rutas del modulo de personas
const personaRoutes = require('./routes/PAPersonaRoutes');
app.use('/api/personas', personaRoutes);
>>>>>>> Stashed changes

app.listen(3000, () => {
<<<<<<< Updated upstream
    console.log('Server is running on port 3000');
=======
  console.log('Server is running on port 3000');
>>>>>>> Stashed changes
});