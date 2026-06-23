const categoriaModel = require('../models/INcategoriaModel');

async function create(req, res) {
  try {
    const { NOM_CATEGORIA, USR_REGISTRO } = req.body;
    if (!NOM_CATEGORIA || !USR_REGISTRO) {
      return res.status(400).json({
        success: false,
        message: 'NOM_CATEGORIA y USR_REGISTRO son obligatorios'
      });
    }
    const nuevo = await categoriaModel.create(req.body);
    res.status(201).json({ success: true, data: nuevo });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
}

async function update(req, res) {
  try {
    const existe = await categoriaModel.getById(req.params.id);
    if (!existe) return res.status(404).json({ success: false, message: 'Categoria no encontrada' });

    const actualizado = await categoriaModel.update(req.params.id, req.body);
    res.json({ success: true, data: actualizado });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
}

module.exports = { create, update };