<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\Order;
use illuminate\Support\Facades\Auth;
class OrderController extends Controller
{
    public function PendingOrders()
    {
        $pendingOrder = Payment::where('status', 'Pending')->orderBy('id', 'DESC')->get();
        return view('admin.backend.order.pendingOrder', compact('pendingOrder'));
    } //End of Mehtod

    public function AdminOrderDetials($id)
    {
        $payment = Payment::where('id', $id)->first();
        $orderItem = Order::where('payment_id', $id)->orderBy('id', 'DESC')->get();

        return view('admin.backend.order.Admin_order_details', compact('orderItem', 'payment'));
    } //End of Mehtod
    public function PendingToConfirm($payment_id)
    {
        Payment::findOrFail($payment_id)->update(['status' => 'confirm']);

        $notification = array(
            'message' => 'Order Confirm Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('admin-confirm-order-list')->with($notification);
    } //End of Mehtod
    public function AdminConfirmOrderList()
    {
        $payment = Payment::where('status', 'confirm')->orderBy('id', 'DESC')->get();

        return view('admin.backend.order.confirmOrder', compact('payment'));

    } //End of Mehtod
    public function InstructorPendingOrders()
    {
        $user = Auth::user()->id;
        $orders = Order::where('instructor_id', $user)->orderBy('id', 'DESC')->get();

        return view('instructor.instructor_order.pendingOrder', compact('orders'));

    } //End of Mehtod
    public function InstructorOrderDetails($id)
    {
        $payment = Payment::where('id', $id)->first();
        $orderItem = Order::where('payment_id', $id)->orderBy('id', 'DESC')->get();

        return view('instructor.instructor_order.orderDetail', compact('orderItem', 'payment'));
    } //End of Mehtod


}
