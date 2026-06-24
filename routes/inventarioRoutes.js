// paquete de express
const express = require('express');
const router = express.Router();

// controlador del modulo de inventario
const ctrl = require('../controllers/inventarioController');

// rutas de almacenes

// insertar almacen
router.post('/almacenes', ctrl.insAlmacen);

// actualizar almacen
router.put('/almacenes/:id', ctrl.updAlmacen);

// rutas de categorias

// insertar categoria
router.post('/categorias', ctrl.insCategoria);

// actualizar categoria
router.put('/categorias/:id', ctrl.updCategoria);

// rutas de items

// insertar item
router.post('/items', ctrl.insItem);

// actualizar item
router.put('/items/:id', ctrl.updItem);

// rutas de asignaciones

// insertar asignacion
router.post('/asignaciones', ctrl.insAsignacion);

// actualizar asignacion
router.put('/asignaciones/:id', ctrl.updAsignacion);

// rutas de reservas de inventario

// insertar reserva
router.post('/reservas', ctrl.insReserva);

// actualizar reserva
router.put('/reservas/:id', ctrl.updReserva);

module.exports = router;
