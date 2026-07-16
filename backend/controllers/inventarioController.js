// controlador del modulo de inventario
const model = require('../models/inventarioModel');

// inserts

// insertar item (puede incluir categoria, almacen, reserva y asignacion en el mismo registro)
const insItem = (req, res) => {
  model.insItem(req.body, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(201).json({ mensaje: 'item de inventario creado', resultado: result });
  });
};

// updates

// actualizar item
const updItem = (req, res) => {
  const datos = { ...req.body, cod_item: req.params.id };
  model.updItem(datos, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json({ mensaje: 'item actualizado', resultado: result });
  });
};

// actualizar categoria
const updCategoria = (req, res) => {
  const datos = { ...req.body, cod_categoria: req.params.id };
  model.updCategoria(datos, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json({ mensaje: 'categoria actualizada', resultado: result });
  });
};

// actualizar almacen
const updAlmacen = (req, res) => {
  const datos = { ...req.body, cod_almacen: req.params.id };
  model.updAlmacen(datos, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json({ mensaje: 'almacen actualizado', resultado: result });
  });
};

// actualizar reserva de inventario
const updReserva = (req, res) => {
  const datos = { ...req.body, cod_reserva: req.params.id };
  model.updReserva(datos, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json({ mensaje: 'reserva de inventario actualizada', resultado: result });
  });
};

// actualizar asignacion a evento
const updAsignacion = (req, res) => {
  const datos = { ...req.body, cod_asignacion: req.params.id };
  model.updAsignacion(datos, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json({ mensaje: 'asignacion actualizada', resultado: result });
  });
};

// soft delete item
const softDeleteItem = (req, res) => {
  const datos = { cod_item: req.params.id, usr_registro: req.body.usr_registro };
  model.softDeleteItem(datos, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json({ mensaje: 'item dado de baja', resultado: result });
  });
};

// soft delete categoria
const softDeleteCategoria = (req, res) => {
  const datos = { cod_categoria: req.params.id, usr_registro: req.body.usr_registro };
  model.softDeleteCategoria(datos, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json({ mensaje: 'categoria desactivada', resultado: result });
  });
};

// soft delete almacen
const softDeleteAlmacen = (req, res) => {
  const datos = { cod_almacen: req.params.id, usr_registro: req.body.usr_registro };
  model.softDeleteAlmacen(datos, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json({ mensaje: 'almacen desactivado', resultado: result });
  });
};

// selects

// obtener todos los items
const selTodos = (req, res) => {
  model.selInventario({}, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json(result[0]);
  });
};

// obtener todas las reservas de inventario
const selReservas = (req, res) => {
  model.selInventario({}, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json(result[3] || []);
  });
};

// obtener todas las asignaciones a evento
const selAsignaciones = (req, res) => {
  model.selInventario({}, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json(result[4] || []);
  });
};

// obtener todas las categorias activas
const selCategorias = (req, res) => {
  model.selInventario({}, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json(result[1] || []);
  });
};

// obtener todos los almacenes activos
const selAlmacenes = (req, res) => {
  model.selInventario({}, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json(result[2] || []);
  });
};

// obtener item por id (detalle completo con categoria y almacen)
const selItem = (req, res) => {
  model.selInventario({ cod_item: req.params.id }, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json(result[0]);
  });
};

// obtener items por categoria
const selPorCategoria = (req, res) => {
  model.selInventario({ cod_categoria: req.params.id }, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json(result[0]);
  });
};

// obtener items por almacen
const selPorAlmacen = (req, res) => {
  model.selInventario({ cod_almacen: req.params.id }, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json(result[0]);
  });
};

// obtener reservas y asignaciones de un evento
const selPorEvento = (req, res) => {
  model.selInventario({ cod_evento: req.params.id }, (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.status(200).json(result[0]);
  });
};

module.exports = {
  insItem,
  updItem,
  updCategoria,
  updAlmacen,
  updReserva,
  updAsignacion,
  softDeleteItem,
  softDeleteCategoria,
  softDeleteAlmacen,
  selTodos,
  selItem,
  selCategorias,
  selAlmacenes,
  selPorCategoria,
  selPorAlmacen,
  selPorEvento,
  selReservas,
  selAsignaciones
};