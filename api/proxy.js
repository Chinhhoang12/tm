// api/proxy.js  –  Vercel Serverless Function
// Nhận request từ frontend → forward đến thuemail.net → trả về kết quả
// Không cần CORS vì cùng domain Vercel với frontend

export default async function handler(req, res) {
  // Chỉ chấp nhận POST
  if (req.method !== "POST") {
    return res.status(405).json({ error: "Method not allowed" });
  }

  // Đọc target từ query: ?target=stock hoặc ?target=buy
  const { target } = req.query;
  const TARGET_URLS = {
    stock: "https://thuemail.net/api_check_stock.php",
    buy:   "https://thuemail.net/api_buy.php",
  };

  const targetUrl = TARGET_URLS[target];
  if (!targetUrl) {
    return res.status(400).json({ error: "Invalid target" });
  }

  try {
    // Đọc body dạng JSON từ frontend
    const body = req.body; // { api_key, product_id, qty }

    // Tạo FormData để gửi đến thuemail.net (họ dùng multipart/form-data)
    const params = new URLSearchParams();
    if (body.api_key)    params.append("api_key",    body.api_key);
    if (body.product_id) params.append("product_id", body.product_id);
    if (body.qty)        params.append("qty",        body.qty);

    const upstream = await fetch(targetUrl, {
      method:  "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body:    params.toString(),
    });

    const data = await upstream.json();

    // Cho phép frontend gọi (CORS headers)
    res.setHeader("Access-Control-Allow-Origin",  "*");
    res.setHeader("Access-Control-Allow-Methods", "POST, OPTIONS");
    res.setHeader("Access-Control-Allow-Headers", "Content-Type");

    return res.status(200).json(data);
  } catch (err) {
    return res.status(502).json({ error: "Upstream error", detail: err.message });
  }
}
