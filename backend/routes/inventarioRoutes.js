// paquete de express
const express = require('express');
const router = express.Router();

// controlador del modulo de inventario
const ctrl = require('../controllers/inventarioController');

// rutas de item

// obtener todos los items
router.get('/item', ctrl.selTodos);

// obtener item por id
router.get('/item/:id', ctrl.selItem);

// insertar item (opcionalmente con categoria, almacen, reserva y asignacion en el mismo registro)
router.post('/item', ctrl.insItem);

// actualizar item
router.put('/item/:id', ctrl.updItem);

// cambio de estado del item (soft delete -> BAJA)
router.put('/item/:id/estado', ctrl.softDeleteItem);

// rutas de categoria

// obtener todas las categorias activas
router.get('/categoria', ctrl.selCategorias);

// obtener items de una categoria
router.get('/categoria/:id/items', ctrl.selPorCategoria);

// actualizar categoria
router.put('/categoria/:id', ctrl.updCategoria);

// cambio de estado de la categoria (soft delete -> ind_activa = false)
router.put('/categoria/:id/estado', ctrl.softDeleteCategoria);

// rutas de almacen

// obtener todos los almacenes activos
router.get('/almacen', ctrl.selAlmacenes);

// obtener items de un almacen
router.get('/almacen/:id/items', ctrl.selPorAlmacen);

// actualizar almacen
router.put('/almacen/:id', ctrl.updAlmacen);

// cambio de estado del almacen (soft delete -> ind_activo = false)
router.put('/almacen/:id/estado', ctrl.softDeleteAlmacen);

// rutas de reserva de inventario

// obtener todas las reservas de inventario
router.get('/reserva', ctrl.selReservas);

// actualizar reserva (incluye cancelacion, que repone la cantidad disponible del item)
router.put('/reserva/:id', ctrl.updReserva);

// rutas de asignacion a evento

// obtener todas las asignaciones
router.get('/asignacion', ctrl.selAsignaciones);

// obtener reservas y asignaciones de un evento
router.get('/evento/:id', ctrl.selPorEvento);

// actualizar asignacion (retornado/perdido ajustan automaticamente las cantidades del item)
router.put('/asignacion/:id', ctrl.updAsignacion);

module.exports = router;