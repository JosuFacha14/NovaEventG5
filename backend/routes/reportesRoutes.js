const express = require('express');
const router = express.Router();
const reportesController = require('../controllers/reportesController');

// ==========================================
// RP_TABLA_REPORTES
// ==========================================
router.get('/reportes', reportesController.getReportes);
router.post('/reportes', reportesController.createReporte);
router.put('/reportes/:id', reportesController.updateReporte);

// ==========================================
// RP_GANANCIAS
// ==========================================
router.get('/ganancias', reportesController.getGanancias);
router.get('/ganancias/:id', reportesController.getGananciaById);
router.post('/ganancias', reportesController.createGanancia);
router.put('/ganancias/:id', reportesController.updateGanancia);

// ==========================================
// RP_REPORTE_INVENTARIO
// ==========================================
router.get('/reportes-inventario', reportesController.getReportesInventario);
router.get('/reportes-inventario/:id', reportesController.getReporteInventarioById);
router.post('/reportes-inventario', reportesController.createReporteInventario);
router.put('/reportes-inventario/:id', reportesController.updateReporteInventario);

// ==========================================
// RP_COSTOS_OPERATIVOS
// ==========================================
router.get('/costos-operativos', reportesController.getCostosOperativos);
router.get('/costos-operativos/:id', reportesController.getCostoOperativoById);
router.post('/costos-operativos', reportesController.createCostoOperativo);
router.put('/costos-operativos/:id', reportesController.updateCostoOperativo);

module.exports = router;

