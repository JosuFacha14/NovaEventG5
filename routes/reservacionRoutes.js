// paquete de express
const express = require('express');
const router = express.Router();

// controlador del modulo de reservacion
const ctrl = require('../controllers/reservacionController');

// rutas de espacio

// obtener todos los espacios
router.get('/espacio', ctrl.selEspacio);

// obtener espacio por id
router.get('/espacio/:id', ctrl.selEspacio);

// insertar espacio
router.post('/espacio', ctrl.insEspacio);

// actualizar espacio
router.put('/espacio/:id', ctrl.updEspacio);

// cambio de estado del espacio (soft delete)
router.put('/espacio/:id/estado', ctrl.softDeleteEspacio);

// rutas de reservacion

// obtener todas las reservaciones
router.get('/reservacion', ctrl.selReservacion);

// obtener reservacion por id
router.get('/reservacion/:id', ctrl.selReservacion);

// insertar reservacion
router.post('/reservacion', ctrl.insReservacion);

// actualizar reservacion
router.put('/reservacion/:id', ctrl.updReservacion);

// cambio de estado de la reservacion (soft delete)
router.put('/reservacion/:id/estado', ctrl.softDeleteReservacion);

// rutas de espacio ocupado

// obtener todos los espacios ocupados
router.get('/espacio-ocupado', ctrl.selEspacioOcupado);

// obtener espacio ocupado por id
router.get('/espacio-ocupado/:id', ctrl.selEspacioOcupado);

// insertar espacio ocupado
router.post('/espacio-ocupado', ctrl.insEspacioOcupado);

// actualizar espacio ocupado
router.put('/espacio-ocupado/:id', ctrl.updEspacioOcupado);

// rutas de historial de reservacion

// obtener todo el historial
router.get('/historial', ctrl.selHistorialReservacion);

// obtener historial por id
router.get('/historial/:id', ctrl.selHistorialReservacion);

// obtener historial por reservacion
router.get('/historial/reservacion/:cod_reservacion', ctrl.selHistorialReservacion);

// insertar historial
router.post('/historial', ctrl.insHistorialReservacion);

module.exports = router;