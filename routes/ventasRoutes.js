const express = require('express');
const router = express.Router();
const ventasController = require('../controllers/ventasController');

// ==========================================
// VE_CATEGORIA_EVENTOS
// ==========================================
router.get('/categorias-eventos', ventasController.obtener);
router.post('/categorias-eventos', ventasController.insertar);
router.put('/categorias-eventos/:id', ventasController.actualizar);
router.patch('/categorias-eventos/:id', ventasController.eliminar);

// ==========================================
// VE_CICLO_EVENTO
// ==========================================
router.get('/ciclos-eventos', ventasController.obtener);
router.post('/ciclos-eventos', ventasController.insertar);
router.put('/ciclos-eventos/:id', ventasController.actualizar);
router.patch('/ciclos-eventos/:id', ventasController.eliminar);

// ==========================================
// VE_EVENTOS
// ==========================================
router.get('/eventos', ventasController.obtener);
router.post('/eventos', ventasController.insertar);
router.put('/eventos/:id', ventasController.actualizar);
router.patch('/eventos/:id', ventasController.eliminar);

// ==========================================
// VE_BOLETOS
// ==========================================
router.get('/boletos', ventasController.obtener);
router.post('/boletos', ventasController.insertar);
router.put('/boletos/:id', ventasController.actualizar);
router.patch('/boletos/:id', ventasController.eliminar);

// ==========================================
// VE_VENTAS
// ==========================================
router.get('/ventas', ventasController.obtener);
router.post('/ventas', ventasController.insertar);
router.put('/ventas/:id', ventasController.actualizar);
router.patch('/ventas/:id', ventasController.eliminar);

// ==========================================
// VE_DETALLE_VENTAS
// ==========================================
router.get('/detalle-ventas', ventasController.obtener);
router.post('/detalle-ventas', ventasController.insertar);
router.put('/detalle-ventas/:id', ventasController.actualizar);
router.patch('/detalle-ventas/:id', ventasController.eliminar);

// ==========================================
// VE_PAGOS
// ==========================================
router.get('/pagos', ventasController.obtener);
router.post('/pagos', ventasController.insertar);
router.put('/pagos/:id', ventasController.actualizar);
router.patch('/pagos/:id', ventasController.eliminar);

module.exports = router;