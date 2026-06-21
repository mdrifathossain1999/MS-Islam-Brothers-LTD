export default {
  async fetch(request, env, ctx) {
    const url = new URL(request.url)

    if (url.pathname === '/api/auth/login' && request.method === 'POST') {
      const { username, password } = await request.json()
      const user = await env.DB.prepare(
        'SELECT id, username, full_name, role FROM users WHERE username = ? AND password = ?'
      ).bind(username, password).first()
      if (!user) return new Response(JSON.stringify({ error: 'Invalid credentials' }), { status: 401, headers: { 'Content-Type': 'application/json' } })
      return new Response(JSON.stringify({ user, token: btoa(JSON.stringify(user)) }), { headers: { 'Content-Type': 'application/json' } })
    }

    if (url.pathname === '/api/dashboard') {
      const [customers, products, sales] = await Promise.all([
        env.DB.prepare('SELECT COUNT(*) as c FROM customers').first(),
        env.DB.prepare('SELECT COUNT(*) as c FROM products').first(),
        env.DB.prepare('SELECT COUNT(*) as c FROM sales').first(),
      ])
      return new Response(JSON.stringify({ totalCustomers: customers.c, totalProducts: products.c, totalSales: sales.c }), { headers: { 'Content-Type': 'application/json' } })
    }

    if (url.pathname === '/api/products') {
      if (request.method === 'GET') {
        const { results } = await env.DB.prepare('SELECT * FROM products ORDER BY id DESC').all()
        return new Response(JSON.stringify(results), { headers: { 'Content-Type': 'application/json' } })
      }
      if (request.method === 'POST') {
        const b = await request.json()
        const { meta } = await env.DB.prepare(
          'INSERT INTO products (name, category, purchase_price, sale_price, stock, barcode, unit, description) VALUES (?,?,?,?,?,?,?,?)'
        ).bind(b.name, b.category, b.purchase_price, b.sale_price, b.stock || 0, b.barcode || null, b.unit || null, b.description || null).run()
        return new Response(JSON.stringify({ id: meta.last_row_id, ...b }), { status: 201, headers: { 'Content-Type': 'application/json' } })
      }
    }

    if (url.pathname.match(/^\/api\/products\/(\d+)$/) && request.method === 'DELETE') {
      const id = url.pathname.match(/^\/api\/products\/(\d+)$/)[1]
      await env.DB.prepare('DELETE FROM products WHERE id = ?').bind(id).run()
      return new Response(JSON.stringify({ success: true }), { headers: { 'Content-Type': 'application/json' } })
    }

    if (url.pathname === '/api/customers') {
      if (request.method === 'GET') {
        const { results } = await env.DB.prepare('SELECT * FROM customers ORDER BY id DESC').all()
        return new Response(JSON.stringify(results), { headers: { 'Content-Type': 'application/json' } })
      }
      if (request.method === 'POST') {
        const b = await request.json()
        const { meta } = await env.DB.prepare(
          'INSERT INTO customers (name, phone, email, address, type_id) VALUES (?,?,?,?,?)'
        ).bind(b.name, b.phone, b.email || null, b.address || null, b.type_id || null).run()
        return new Response(JSON.stringify({ id: meta.last_row_id, ...b }), { status: 201, headers: { 'Content-Type': 'application/json' } })
      }
    }

    if (url.pathname === '/api/sales') {
      if (request.method === 'GET') {
        const { results } = await env.DB.prepare(
          'SELECT s.*, c.name as customer_name FROM sales s LEFT JOIN customers c ON s.customer_id = c.id ORDER BY s.id DESC LIMIT 50'
        ).all()
        return new Response(JSON.stringify(results), { headers: { 'Content-Type': 'application/json' } })
      }
      if (request.method === 'POST') {
        const b = await request.json()
        const { meta } = await env.DB.prepare(
          'INSERT INTO sales (customer_id, total_amount, paid_amount, due_amount, payment_method, invoice_number, created_at) VALUES (?,?,?,?,?,?,?)'
        ).bind(b.customer_id, b.total_amount, b.paid_amount, b.due_amount, b.payment_method, b.invoice_number, new Date().toISOString()).run()
        return new Response(JSON.stringify({ id: meta.last_row_id, ...b }), { status: 201, headers: { 'Content-Type': 'application/json' } })
      }
    }

    return new Response('Not Found', { status: 404 })
  }
}
