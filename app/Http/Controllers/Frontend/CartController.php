<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;

use App\Models\Course;
use Auth;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function addToCart(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        // Check if the course is already in the cart
        $CartItem = Cart::search(function ($cartItem, $rowId) use ($id) {
            return $cartItem->id == $id;
        });

        if (Session::has('coupon')) {
            Session::forget('coupon');
        }
        if ($CartItem->isNotEmpty()) {
            return response()->json(['error' => 'The Course is already in your Cart']);
        }

        $price = $course->discount_price ?? $course->selling_price;

        Cart::add([
            'id' => $id,
            'name' => $request->course_name,
            'qty' => 1,
            'price' => $price,
            'weight' => 1,
            'options' => [
                'image' => $course->course_image,
                'slug' => $course->course_name_slug,
                'instructor' => $course->instructor,
            ]
        ]);

        return response()->json(['success' => 'Added To Cart']);
    }
    // End of Method
    public function CartData()
    {
        $cart = Cart::content();
        $cartTotal = Cart::total();
        $cartQty = Cart::count();

        return response()->json(array(
            'cart' => $cart,
            'cartTotal' => $cartTotal,
            'cartQty' => $cartQty,
        ));

    }
    // End of Method
    public function AddMiniCart()
    {
        $cart = Cart::content();
        $cartTotal = Cart::total();
        $cartQty = Cart::count();

        return response()->json(array(
            'cart' => $cart,
            'cartTotal' => $cartTotal,
            'cartQty' => $cartQty,
        ));

    }
    // End of Method
    public function RemoveMiniCart($id)
    {
        Cart::remove($id);

        return response()->json(['success' => 'Course Removed from Cart']);

    }
    // End of Method
    public function MyCart()
    {
        return view('frontend.mycart.view_mycart');
    }
    // End of Method
    public function GetCartCourse()
    {
        $cart = Cart::content();
        $cartTotal = Cart::total();
        $cartQty = Cart::count();

        return response()->json(array(
            'cart' => $cart,
            'cartTotal' => $cartTotal,
            'cartQty' => $cartQty,
        ));

    }
    // End of Method
    public function RemoveCart($id)
    {
        Cart::remove($id);
        if (Session::has('coupon')) {
            $coupon_name = Session::get('coupon')['coupon_name'];
            $coupon = Coupon::where('coupon_name', $coupon_name)->first();
            session()->put('coupon', [
                'coupon_name' => $coupon->coupon_name,
                'coupon_discount' => $coupon->discount,
                'discount_amount' => round(Cart::total() *
                    $coupon->coupon_discount / 100),
                'total_amount' => Cart::total() - round(Cart::total() *
                    $coupon->coupon_discount / 100),

            ]);
        }
        return response()->json(['success' => 'Course Removed from Cart']);

    }    // End of Method
    public function CouponApply(Request $request)
    {
        $coupon = Coupon::where('coupon_name', $request->coupon_name)
            ->where('validaty', '>=', Carbon::now()->format('Y-m-d'))
            ->first();

        if ($coupon) {
            session()->put('coupon', [
                'coupon_name' => $coupon->coupon_name,
                'coupon_discount' => $coupon->discount,
                'discount_amount' => round(Cart::total() *
                    $coupon->coupon_discount / 100),
                'total_amount' => Cart::total() - round(Cart::total() *
                    $coupon->coupon_discount / 100),

            ]);
            return response()->json(['validaty' => true, 'success' => 'Coupon Applied']);
        } else {
            return response()->json(['error' => 'Invalid Coupon']);
        }

    }    // End of Method
    public function CouponCalculation(Request $request)
    {
        if (Session::has('coupon')) {
            return response()->json(array(
                'subtotal' => Cart::total(),
                'coupon_name' => session()->get('coupon')['coupon_name'],
                'coupon_discount' => session()->get('coupon')['coupon_discount'],
                'discount_amount' => session()->get('coupon')['discount_amount'],
                'total_amount' => session()->get('coupon')['total_amount'],
            ));
        } else {
            return response()->json(array(
                'total' => Cart::total(),
            ));
        }

    }    // End of Method
    public function CouponRemove()
    {
        Session::forget('coupon');
        return response()->json(['success' => 'Coupon Removed']);

    }    // End of Method
    public function CheckoutCreate()
    {
        if (Auth::check()) {
            if (Cart::total() > 0) {
                $content = Cart::content();
                $cartQty = Cart::total();
                $cartCount = Cart::count();
                return view('frontend.checkout.view_checkout', compact('content', 'cartQty', 'cartCount'));
            } else {
                $notification = array(
                    'message' => 'Cart is Empty',
                    'alert-type' => 'error'
                );
                return redirect()->to('/')->with($notification);
            }
        } else {
            $notification = array(

            );
            return redirect()->to('/login')->with($notification);
        }

    }    // End of Method
}
