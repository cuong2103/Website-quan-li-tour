<?php

class PaymentController
{
    private $paymentModel;

    public function __construct()
    {
        $this->paymentModel = new PaymentModel();
    }

    public function list()
    {
        $booking_id = $_GET['booking_id'];
        $payments = $this->paymentModel->getAllByBooking($booking_id);

        require_once './views/admin/payments/list.php';
    }

    public function create()
    {
        $booking_id = $_GET['booking_id'];
        require_once './views/admin/payments/create.php';
    }

    public function store()
    {
        $data = [
            'booking_id'     => $_POST['booking_id'],
            'payment_method' => $_POST['payment_method'],
            'amount'         => $_POST['amount'],
            'type'           => $_POST['type'],
            'status'         => $_POST['status'],
            'notes'          => $_POST['notes'],
            'payment_date'   => $_POST['payment_date'],
            'created_by'     => 1 // admin fake
        ];

        $this->paymentModel->store($data);

        header("Location: " . BASE_URL . "?act=booking-detail&id=" . $data['booking_id'] . "&tab=payments");
        exit();
    }

    public function edit()
    {
        $id = $_GET['id'];
        $payment = $this->paymentModel->findById($id);

        require_once './views/admin/payments/edit.php';
    }

    public function update()
    {
        $id = $_POST['id'];
        $data = [
            'payment_method' => $_POST['payment_method'],
            'amount'         => $_POST['amount'],
            'type'           => $_POST['type'],
            'status'         => $_POST['status'],
            'notes'          => $_POST['notes'],
            'payment_date'   => $_POST['payment_date']
        ];

        $this->paymentModel->update($id, $data);

        header("Location: " . BASE_URL . "?act=payment-detail&id=" . $id);
        exit();
    }

    public function detail()
    {
        $id = $_GET['id'];
        $payment = $this->paymentModel->findById($id);

        require_once './views/admin/payments/detail.php';
    }

    public function delete()
    {
        $id = $_GET['id'];

        $payment = $this->paymentModel->findById($id);
        $booking_id = $payment['booking_id'];

        $this->paymentModel->destroy($id);

        header("Location: " . BASE_URL . "?act=booking-detail&id=$booking_id&tab=payments");
        exit();
    }
}
