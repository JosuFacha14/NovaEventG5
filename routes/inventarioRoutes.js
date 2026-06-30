const express = require('express');
const router = express.Router();

const inventarioController = require('../controllers/inventarioController');

// Obtener inventario
router.get('/', inventarioController.getInventario);

// Registrar inventario
router.post('/', inventarioController.postInventario);

// Actualizar o eliminar lógicamente
router.put('/', inventarioController.putInventario);

module.exports = router;