// paquete de express
const express = require('express');
const router = express.Router();

// controlador del modulo de inventario
const ctrl = require('../controllers/inventarioController');

// ==========================================
// ALMACENES
// ==========================================
router.get('/almacenes', ctrl.getAllAlmacenes);
router.post('/almacenes', ctrl.insAlmacen);
router.put('/almacenes/:id', ctrl.updAlmacen);
router.put('/almacenes/:id/delete', ctrl.delAlmacen);

// ==========================================
// CATEGORIAS
// ==========================================
router.get('/categorias', ctrl.getAllCategorias);
router.post('/categorias', ctrl.insCategoria);
router.put('/categorias/:id', ctrl.updCategoria);
router.put('/categorias/:id/delete', ctrl.delCategoria);

// ==========================================
// ITEMS
// ==========================================
router.get('/items', ctrl.getAllItems);
router.post('/items', ctrl.insItem);
router.put('/items/:id', ctrl.updItem);
router.put('/items/:id/delete', ctrl.delItem);

// ==========================================
// ASIGNACIONES
// ==========================================
router.get('/asignaciones', ctrl.getAllAsignaciones);
router.post('/asignaciones', ctrl.insAsignacion);
router.put('/asignaciones/:id', ctrl.updAsignacion);
router.put('/asignaciones/:id/delete', ctrl.delAsignacion);

// ==========================================
// RESERVAS DE INVENTARIO
// ==========================================
router.get('/reservas', ctrl.getAllReservas);
router.post('/reservas', ctrl.insReserva);
router.put('/reservas/:id', ctrl.updReserva);
router.put('/reservas/:id/delete', ctrl.delReserva);

module.exports = router;