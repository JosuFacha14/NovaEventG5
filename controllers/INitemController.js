const itemModel = require('../models/INitemModel');

async function create(req, res) {
  try {
    const { NOM_ITEM, COD_CATEGORIA, COD_ALMACEN, CAN_TOTAL, USR_REGISTRO } = req.body;
    if (!NOM_ITEM || !COD_CATEGORIA || !COD_ALMACEN || CAN_TOTAL == null || !USR_REGISTRO) {
      return res.status(400).json({
        success: false,
        message: 'NOM_ITEM, COD_CATEGORIA, COD_ALMACEN, CAN_TOTAL y USR_REGISTRO son obligatorios'
      });
    }
    const nuevo = await itemModel.create(req.body);
    res.status(201).json({ success: true, data: nuevo });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
}

async function update(req, res) {
  try {
    const existe = await itemModel.getById(req.params.id);
    if (!existe) return res.status(404).json({ success: false, message: 'Item no encontrado' });

    const actualizado = await itemModel.update(req.params.id, req.body);
    res.json({ success: true, data: actualizado });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
}

module.exports = { create, update };