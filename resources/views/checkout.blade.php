<h2>Customer Check-Out</h2>

<form method="POST" action="/checkout/process">
    @csrf
    <label>Reservation ID:</label>
    <input type="number" name="reservation_id" required />
    <button type="submit">Check Out</button>
</form>
