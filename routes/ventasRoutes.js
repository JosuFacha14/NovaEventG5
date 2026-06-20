const express = require('express');

const router = express.Router();

const ventasController = require('../controllers/ventasController');

router.get('/ventas', ventasController.obtener);

router.post('/ventas', ventasController.insertar);

router.put('/ventas', ventasController.actualizar);

module.exports = router;