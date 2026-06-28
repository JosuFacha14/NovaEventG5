const express = require('express');
const router = express.Router();

const personasController = require('../controllers/personasController');

// ==========================================
// PA_PERSONAS
// ==========================================
router.get('/personas', personasController.obtener);
router.get('/personas/:id', personasController.obtener);
router.post('/personas', personasController.insertar);
router.put('/personas/:id', personasController.actualizar);
router.patch('/personas/:id', personasController.softDelete);

// ==========================================
// PA_TELEFONOS
// ==========================================
router.get('/telefonos', personasController.obtener);
router.get('/telefonos/:id', personasController.obtener);
router.post('/telefonos', personasController.insertar);
router.put('/telefonos/:id', personasController.actualizar);

// ==========================================
// REL_PERSONAS_TELEFONOS
// ==========================================
router.get('/personas-telefonos', personasController.obtener);
router.get('/personas-telefonos/:id', personasController.obtener);
router.post('/personas-telefonos', personasController.insertar);
router.put('/personas-telefonos/:id', personasController.actualizar);

// ==========================================
// PA_CORREOS
// ==========================================
router.get('/correos', personasController.obtener);
router.get('/correos/:id', personasController.obtener);
router.post('/correos', personasController.insertar);
router.put('/correos/:id', personasController.actualizar);

// ==========================================
// REL_PERSONAS_CORREOS
// ==========================================
router.get('/personas-correos', personasController.obtener);
router.get('/personas-correos/:id', personasController.obtener);
router.post('/personas-correos', personasController.insertar);
router.put('/personas-correos/:id', personasController.actualizar);

// ==========================================
// PA_TIPO_USUARIOS
// ==========================================
router.get('/tipos-usuarios', personasController.obtener);
router.get('/tipos-usuarios/:id', personasController.obtener);
router.post('/tipos-usuarios', personasController.insertar);
router.put('/tipos-usuarios/:id', personasController.actualizar);

// ==========================================
// USUARIOS
// ==========================================
router.get('/usuarios', personasController.obtener);
router.get('/usuarios/:id', personasController.obtener);
router.post('/usuarios', personasController.insertar);
router.put('/usuarios/:id', personasController.actualizar);
router.patch('/usuarios/:id', personasController.softDelete);

// ==========================================
// PA_TIPO_CLIENTES
// ==========================================
router.get('/tipos-clientes', personasController.obtener);
router.get('/tipos-clientes/:id', personasController.obtener);
router.post('/tipos-clientes', personasController.insertar);
router.put('/tipos-clientes/:id', personasController.actualizar);

// ==========================================
// PA_CLIENTES
// ==========================================
router.get('/clientes', personasController.obtener);
router.get('/clientes/:id', personasController.obtener);
router.post('/clientes', personasController.insertar);
router.put('/clientes/:id', personasController.actualizar);

// ==========================================
// PA_EMPLEADOS
// ==========================================
router.get('/empleados', personasController.obtener);
router.get('/empleados/:id', personasController.obtener);
router.post('/empleados', personasController.insertar);
router.put('/empleados/:id', personasController.actualizar);

// ==========================================
// PA_PROVEEDORES
// ==========================================
router.get('/proveedores', personasController.obtener);
router.get('/proveedores/:id', personasController.obtener);
router.post('/proveedores', personasController.insertar);
router.put('/proveedores/:id', personasController.actualizar);

module.exports = router;