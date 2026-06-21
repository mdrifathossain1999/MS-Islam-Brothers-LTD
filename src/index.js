import { Hono } from 'hono'
import { cors } from 'hono/cors'

const app = new Hono()

app.use('/api/*', cors())

app.use('/api/*', async (c, next) => {
  const auth = c.req.header('Authorization')
  if (c.req.path === '/api/auth/login') return next()
  if (!auth) return c.json({ error: 'Unauthorized' }, 401)
  return next()
})

app.post('/api/auth/login', async (c) => {
  const { username, password } = await c.req.json()
  const user = await c.env.DB.prepare(
    'SELECT id, username, full_name, role FROM users WHERE username = ? AND password = ?'
  ).bind(username, password).first()
  if (!user) return c.json({ error: 'Invalid credentials' }, 401)
  return c.json({ user, token: btoa(JSON.stringify(user)) })
})

app.get('/api/dashboard', async (c) => {
  const [totalCustomers, totalProducts, totalSales] = await Promise.all([
    c.env.DB.prepare('SELECT COUNT(*) as count FROM customers').first(),
    c.env.DB.prepare('SELECT COUNT(*) as count FROM products').first(),
    c.env.DB.prepare('SELECT COUNT(*) as count FROM sales').first(),
  ])
  return c.json({
    totalCustomers: totalCustomers.count,
    totalProducts: totalProducts.count,
    totalSales: totalSales.count,
  })
})

app.get('/api/products', async (c) => {
  const { results } = await c.env.DB.prepare(
    'SELECT * FROM products ORDER BY id DESC'
  ).all()
  return c.json(results)
})

app.get('/api/products/:id', async (c) => {
  const product = await c.env.DB.prepare(
    'SELECT * FROM products WHERE id = ?'
  ).bind(c.req.param('id')).first()
  if (!product) return c.json({ error: 'Not found' }, 404)
  return c.json(product)
})

app.post('/api/products', async (c) => {
  const body = await c.req.json()
  const { success, meta } = await c.env.DB.prepare(
    `INSERT INTO products (name, category, purchase_price, sale_price, stock, barcode, unit, description)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?)`
  ).bind(body.name, body.category, body.purchase_price, body.sale_price, body.stock || 0, body.barcode || null, body.unit || null, body.description || null).run()
  return c.json({ id: meta.last_row_id, ...body }, 201)
})

app.put('/api/products/:id', async (c) => {
  const body = await c.req.json()
  await c.env.DB.prepare(
    `UPDATE products SET name=?, category=?, purchase_price=?, sale_price=?, stock=?, barcode=?, unit=?, description=?
     WHERE id=?`
  ).bind(body.name, body.category, body.purchase_price, body.sale_price, body.stock, body.barcode, body.unit, body.description, c.req.param('id')).run()
  return c.json({ success: true })
})

app.delete('/api/products/:id', async (c) => {
  await c.env.DB.prepare('DELETE FROM products WHERE id = ?').bind(c.req.param('id')).run()
  return c.json({ success: true })
})

app.get('/api/customers', async (c) => {
  const { results } = await c.env.DB.prepare(
    'SELECT * FROM customers ORDER BY id DESC'
  ).all()
  return c.json(results)
})

app.get('/api/customers/:id', async (c) => {
  const customer = await c.env.DB.prepare(
    'SELECT * FROM customers WHERE id = ?'
  ).bind(c.req.param('id')).first()
  if (!customer) return c.json({ error: 'Not found' }, 404)
  return c.json(customer)
})

app.post('/api/customers', async (c) => {
  const body = await c.req.json()
  const { success, meta } = await c.env.DB.prepare(
    `INSERT INTO customers (name, phone, email, address, type_id)
     VALUES (?, ?, ?, ?, ?)`
  ).bind(body.name, body.phone, body.email || null, body.address || null, body.type_id || null).run()
  return c.json({ id: meta.last_row_id, ...body }, 201)
})

app.get('/api/sales', async (c) => {
  const { results } = await c.env.DB.prepare(
    'SELECT s.*, c.name as customer_name FROM sales s LEFT JOIN customers c ON s.customer_id = c.id ORDER BY s.id DESC LIMIT 50'
  ).all()
  return c.json(results)
})

app.post('/api/sales', async (c) => {
  const body = await c.req.json()
  const { success, meta } = await c.env.DB.prepare(
    `INSERT INTO sales (customer_id, total_amount, paid_amount, due_amount, payment_method, invoice_number, created_at)
     VALUES (?, ?, ?, ?, ?, ?, ?)`
  ).bind(body.customer_id, body.total_amount, body.paid_amount, body.due_amount, body.payment_method, body.invoice_number, new Date().toISOString()).run()
  return c.json({ id: meta.last_row_id, ...body }, 201)
})

export default app
