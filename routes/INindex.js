const express = require('express');
const router = express.Router();

router.use('/almacenes', require('./INalmacenRoutes'));
router.use('/categorias', require('./INcategoriaRoutes'));
router.use('/items', require('./INitemRoutes'));
router.use('/asignaciones', require('./INasignacionRoutes'));
router.use('/reservas', require('./INreservaRoutes'));

module.exports = router;