const mysql = require('mysql');
const express = require('express');
const app = express();
const bp = require('body-parser');
const db = require('./config/db');

app.use(bp.json());

app.use(
    '/api',
    require('./routes/ventasRoutes')
);

app.listen(3000, () => {
  console.log('Server is running on port 3000');
});