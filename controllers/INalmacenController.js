const almacenModel = require('../models/INalmacenModel');

async function create(req, res) {
  try {
    const { NOM_ALMACEN, CAN_CAPACIDAD, USR_REGISTRO } = req.body;
    if (!NOM_ALMACEN || !USR_REGISTRO) {
      return res.status(400).json({
        success: false,
        message: 'NOM_ALMACEN y USR_REGISTRO son obligatorios'
      });
    }
    const nuevo = await almacenModel.create(req.body);
    res.status(201).json({ success: true, data: nuevo });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
}

async function update(req, res) {
  try {
    const existe = await almacenModel.getById(req.params.id);
    if (!existe) return res.status(404).json({ success: false, message: 'Almacen no encontrado' });

    const actualizado = await almacenModel.update(req.params.id, req.body);
    res.json({ success: true, data: actualizado });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
}

module.exports = { create, update };