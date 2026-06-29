// paquete de express
const express = require('express');
const router = express.Router();

// controlador del modulo de inventario
const ctrl = require('../controllers/inventarioController');

// ==========================================
// ALMACENES
// ==========================================

// obtener almacenes
router.get('/almacenes', ctrl.getAllAlmacenes);

// insertar almacen
router.post('/almacenes', ctrl.insAlmacen);

// actualizar almacen
router.put('/almacenes/:id', ctrl.updAlmacen);

// soft delete almacen
router.patch('/almacenes/:id', ctrl.delAlmacen);

// ==========================================
// CATEGORIAS
// ==========================================

// obtener categorias
router.get('/categorias', ctrl.getAllCategorias);

// insertar categoria
router.post('/categorias', ctrl.insCategoria);

// actualizar categoria
router.put('/categorias/:id', ctrl.updCategoria);

// soft delete categoria
router.patch('/categorias/:id', ctrl.delCategoria);

// ==========================================
// ITEMS
// ==========================================

// obtener items
router.get('/items', ctrl.getAllItems);

// insertar item
router.post('/items', ctrl.insItem);

// actualizar item
router.put('/items/:id', ctrl.updItem);

// soft delete item
router.patch('/items/:id', ctrl.delItem);

// ==========================================
// ASIGNACIONES
// ==========================================

// obtener asignaciones
router.get('/asignaciones', ctrl.getAllAsignaciones);

// insertar asignacion
router.post('/asignaciones', ctrl.insAsignacion);

// actualizar asignacion
router.put('/asignaciones/:id', ctrl.updAsignacion);

// soft delete asignacion
router.patch('/asignaciones/:id', ctrl.delAsignacion);

// ==========================================
// RESERVAS DE INVENTARIO
// ==========================================

// obtener reservas
router.get('/reservas', ctrl.getAllReservas);

// insertar reserva
router.post('/reservas', ctrl.insReserva);

// actualizar reserva
router.put('/reservas/:id', ctrl.updReserva);

// soft delete reserva
router.patch('/reservas/:id', ctrl.delReserva);

module.exports = router;
