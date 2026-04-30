<!-- Payment Modal - Fintech Style (GoPay/OVO/ShopeePay Inspired) -->
<div class="message-modal" id="uploadModal">
    <div class="message-modal-content" style="max-width: 480px; padding: 0; border-radius: 24px; background: #ffffff; box-shadow: 0 20px 60px rgba(0,0,0,0.15); overflow: hidden; animation: slideUp 0.3s ease-out;">
        
        <!-- Header Section -->
        <div style="background: linear-gradient(135deg, #D2691E 0%, #8B4513 100%); padding: 1.5rem 1.5rem 3rem 1.5rem; position: relative; overflow: hidden;">
            <!-- Decorative circles -->
            <div style="position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="position: absolute; bottom: -30px; left: -30px; width: 100px; height: 100px; background: rgba(255,255,255,0.08); border-radius: 50%;"></div>
            
            <button onclick="closeUploadModal()" style="position: absolute; top: 1rem; right: 1rem; background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); border: none; width: 32px; height: 32px; border-radius: 50%; color: white; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; transition: all 0.2s; z-index: 10;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">×</button>
            
            <div style="position: relative; z-index: 1;">
                <h2 style="color: white; font-size: 1.5rem; font-weight: 700; margin: 0 0 0.5rem 0; font-family: 'Outfit', sans-serif;">Payment Confirmation</h2>
                <p style="color: rgba(255,255,255,0.9); font-size: 0.9rem; margin: 0;">Scan QR code and upload proof of payment</p>
            </div>
        </div>

        <!-- Order Summary Card -->
        <div style="margin: -2rem 1.5rem 1.5rem 1.5rem; background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 8px 24px rgba(0,0,0,0.08); border: 1px solid #f0f0f0; position: relative; z-index: 2;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <span style="color: #666; font-size: 0.85rem;">Order ID</span>
                <span id="orderIdDisplay" style="color: #333; font-weight: 600; font-size: 0.9rem; font-family: 'Courier New', monospace;">#ORD-12345</span>
            </div>
            <div style="border-top: 1px dashed #e0e0e0; padding-top: 1rem;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: #666; font-size: 0.9rem;">Total Payment</span>
                    <div style="text-align: right;">
                        <div id="totalPaymentDisplay" style="color: #D2691E; font-size: 1.8rem; font-weight: 800; line-height: 1; font-family: 'Outfit', sans-serif;">Rp 150.000</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div style="padding: 0 1.5rem 1.5rem 1.5rem;">
            
            <!-- QR Code Section -->
            <div style="background: linear-gradient(135deg, #FFF8DC 0%, #FFFAF0 100%); border-radius: 16px; padding: 1.5rem; margin-bottom: 1.5rem; text-align: center; border: 2px solid #F4A460;">
                <div style="display: inline-block; margin-bottom: 0.75rem;">
                    <span style="background: linear-gradient(135deg, #D2691E, #8B4513); color: white; padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Scan to Pay</span>
                </div>
                
                <div style="background: white; padding: 1.5rem; border-radius: 12px; display: inline-block; box-shadow: 0 4px 12px rgba(0,0,0,0.08); margin-bottom: 1rem;">
                    @php
                        $qrisImage = \App\Models\PaymentSetting::getQrisImage();
                        $qrisImageWithTimestamp = $qrisImage . (strpos($qrisImage, '?') !== false ? '&' : '?') . 't=' . time();
                    @endphp
                    <img src="{{ $qrisImageWithTimestamp }}" 
                         alt="QRIS" 
                         style="width: 200px; height: 200px; object-fit: contain; display: block;" 
                         onerror="this.src='https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=DapoerBudessQRISPayment_Mockup';">
                </div>
                
                <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Logo_QRIS.svg/1200px-Logo_QRIS.svg.png" alt="QRIS" style="height: 20px;">
                    <span style="color: #8B4513; font-weight: 700; font-size: 0.95rem;">QRIS Payment</span>
                </div>
                <div style="color: #666; font-size: 0.85rem;">Dapoer Budess Bakery</div>
            </div>

            <!-- Instructions -->
            <div style="background: #f8f9fa; border-radius: 12px; padding: 1.25rem; margin-bottom: 1.5rem;">
                <div style="color: #333; font-weight: 700; font-size: 0.9rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <span style="display: inline-block; width: 24px; height: 24px; background: linear-gradient(135deg, #D2691E, #8B4513); border-radius: 50%; color: white; font-size: 0.75rem; display: flex; align-items: center; justify-content: center;">ℹ️</span>
                    How to Pay
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <div style="display: flex; gap: 0.75rem; align-items: flex-start;">
                        <span style="flex-shrink: 0; width: 24px; height: 24px; background: #D2691E; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700;">1</span>
                        <span style="color: #555; font-size: 0.85rem; line-height: 1.5;">Open your e-wallet or mobile banking app</span>
                    </div>
                    <div style="display: flex; gap: 0.75rem; align-items: flex-start;">
                        <span style="flex-shrink: 0; width: 24px; height: 24px; background: #D2691E; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700;">2</span>
                        <span style="color: #555; font-size: 0.85rem; line-height: 1.5;">Select "Scan QR" or "QRIS" menu</span>
                    </div>
                    <div style="display: flex; gap: 0.75rem; align-items: flex-start;">
                        <span style="flex-shrink: 0; width: 24px; height: 24px; background: #D2691E; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700;">3</span>
                        <span style="color: #555; font-size: 0.85rem; line-height: 1.5;">Scan the QR code above</span>
                    </div>
                    <div style="display: flex; gap: 0.75rem; align-items: flex-start;">
                        <span style="flex-shrink: 0; width: 24px; height: 24px; background: #D2691E; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700;">4</span>
                        <span style="color: #555; font-size: 0.85rem; line-height: 1.5;">Complete payment and upload proof below</span>
                    </div>
                </div>
            </div>
        
            <!-- Upload Form -->
            <form id="uploadProofForm" onsubmit="submitPaymentProof(event)">
                <input type="hidden" id="uploadOrderId" name="order_id">
                
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; color: #333; font-weight: 700; font-size: 0.9rem; margin-bottom: 0.75rem;">Upload Payment Proof</label>
                    
                    <div style="border: 2px dashed #D2691E; border-radius: 12px; padding: 2rem 1.5rem; text-align: center; cursor: pointer; transition: all 0.3s; background: linear-gradient(135deg, #FFFAF0 0%, #FFF8DC 100%); position: relative;" 
                         onclick="document.getElementById('proofInput').click()" 
                         onmouseover="this.style.borderColor='#8B4513'; this.style.background='linear-gradient(135deg, #FFF8DC 0%, #FFFAF0 100%)'" 
                         onmouseout="this.style.borderColor='#D2691E'; this.style.background='linear-gradient(135deg, #FFFAF0 0%, #FFF8DC 100%)'">
                        
                        <div id="proofPreviewPlaceholder">
                            <div style="display: inline-block; width: 56px; height: 56px; background: linear-gradient(135deg, #D2691E, #8B4513); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; box-shadow: 0 4px 12px rgba(210, 105, 30, 0.3);">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="17 8 12 3 7 8"></polyline>
                                    <line x1="12" y1="3" x2="12" y2="15"></line>
                                </svg>
                            </div>
                            <div style="color: #8B4513; font-weight: 700; font-size: 0.95rem; margin-bottom: 0.25rem;">Click to upload</div>
                            <div style="color: #999; font-size: 0.8rem;">JPG, PNG (Max 2MB)</div>
                        </div>
                        
                        <img id="proofPreviewImg" style="max-width: 100%; max-height: 200px; display: none; border-radius: 8px; margin: 0 auto;">
                    </div>
                    
                    <input type="file" id="proofInput" name="payment_proof" accept="image/png, image/jpeg, image/jpg" style="display: none;" onchange="previewProof()" required>
                </div>
                
                <!-- Status Badge -->
                <div style="background: #FFF3CD; border: 1px solid #FFE69C; border-radius: 8px; padding: 0.75rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <span style="font-size: 1.2rem;">⏳</span>
                    <span style="color: #856404; font-size: 0.85rem; font-weight: 600;">Waiting for verification after upload</span>
                </div>
                
                <!-- Action Buttons -->
                <div style="display: flex; gap: 0.75rem;">
                    <button type="button" onclick="closeUploadModal()" 
                            style="flex: 1; background: #f1f3f5; color: #495057; border: none; padding: 1rem; border-radius: 12px; cursor: pointer; font-weight: 600; font-size: 0.95rem; transition: all 0.2s; font-family: 'Outfit', sans-serif;" 
                            onmouseover="this.style.background='#e9ecef'" 
                            onmouseout="this.style.background='#f1f3f5'">
                        Cancel
                    </button>
                    <button type="submit" id="submitProofBtn" 
                            style="flex: 2; background: linear-gradient(135deg, #D2691E 0%, #8B4513 100%); border: none; padding: 1rem; border-radius: 12px; cursor: pointer; font-weight: 700; color: white; font-size: 0.95rem; box-shadow: 0 4px 12px rgba(210, 105, 30, 0.3); transition: all 0.2s; font-family: 'Outfit', sans-serif;" 
                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(210, 105, 30, 0.4)'" 
                            onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 12px rgba(210, 105, 30, 0.3)'">
                        Submit Payment Proof
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="message-overlay" id="uploadOverlay" onclick="closeUploadModal()" style="background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);"></div>

<style>
@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 768px) {
    #uploadModal .message-modal-content {
        max-width: 95% !important;
        margin: 1rem;
    }
}
</style>
