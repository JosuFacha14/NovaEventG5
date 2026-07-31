//constantes para el paquete mysql, express, body-parser y la conexion a la base de datos
const mysql = require('mysql2');
const express = require('express');
const app = express();
const bp = require('body-parser');
const db = require('./config/db');

//lic, aqui como coordinador del grupo 5 le dire quienes 
//trabajaron en el proyecto y que modulo les toco a cada uno, 
//para que usted pueda evaluarnos de manera individual y grupal

//trabajo en el modulo de personas: TODO LOS INTEGRANTES

//trabajo en el modulo de reservacion:
//Jorge Alberto Maradiaga Molina 20152502011
//Josue David Ortiz Ortiz 20231003161

//trabajo en el modulo de inventario:
//Cindy Michelle Osorto González 20211001885
//Joel fernando valladares CRUZ 20211024825

//trabajo en el modulo de ventas:
//Juan Carlos López Avila 20181033368
//Jaziel Jafeth Funez Andino 20201003788

//trabajo en el modulo de reportes
//Carlos David Padilla Velásquez 20221003965  
//Angie Rebeca Aguirre Rivera 20191007529, modulo Reportes

//esta reparticion aplica para todo el proyecto y sus distintas fases
//mysql creacion de tablas y procedimientos, 
//creacion de apis apuntando a los procedimientos,
//creacion de las interfaces utilizando LARAVEL 13+ADMINLTE 4 para consumir dichas apis


//para encender los servicios
//npm run dev /frontend
//php artisan serve /frontend
//node index /backend

//crear las tablas y los procedimientos almacenados de

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