const express = require('express');
const router = express.Router();
const ventasController = require('../controllers/ventasController');

// ==========================================
// VE_CATEGORIA_EVENTOS
// ==========================================
router.get('/categorias-eventos', ventasController.obtener);
router.post('/categorias-eventos', ventasController.insertar);
router.put('/categorias-eventos/:id', ventasController.actualizar);

// ==========================================
// VE_CICLO_EVENTO
// ==========================================
router.get('/ciclos-eventos', ventasController.obtener);
router.post('/ciclos-eventos', ventasController.insertar);
router.put('/ciclos-eventos/:id', ventasController.actualizar);

// ==========================================
// VE_EVENTOS
// ==========================================
router.get('/eventos', ventasController.obtener);
router.post('/eventos', ventasController.insertar);
router.put('/eventos/:id', ventasController.actualizar);

// ==========================================
// VE_BOLETOS
// ==========================================
router.get('/boletos', ventasController.obtener);
router.post('/boletos', ventasController.insertar);
router.put('/boletos/:id', ventasController.actualizar);

// ==========================================
// VE_VENTAS
// ==========================================
router.get('/ventas', ventasController.obtener);
router.post('/ventas', ventasController.insertar);
router.put('/ventas/:id', ventasController.actualizar);

// ==========================================
// VE_DETALLE_VENTAS
// ==========================================
router.get('/detalle-ventas', ventasController.obtener);
router.post('/detalle-ventas', ventasController.insertar);
router.put('/detalle-ventas/:id', ventasController.actualizar);

// ==========================================
// VE_PAGOS
// ==========================================
router.get('/pagos', ventasController.obtener);
router.post('/pagos', ventasController.insertar);
router.put('/pagos/:id', ventasController.actualizar);

module.exports = router;