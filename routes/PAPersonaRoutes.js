const express = require('express');
const router = express.Router();
const controller = require('../controllers/PAPersonaController');

// PUT  /api/personas/:id             →  Actualizar persona
router.put('/:id', controller.update);

// PUT  /api/personas/:id/desactivar  →  Soft delete (desactivar usuario)
router.put('/:id/desactivar', controller.softDelete);

module.exports = router;
