// components/PrintLabels.tsx

import React, { useState } from 'react';
import { Asset } from '../types';

interface PrintLabelsProps {
  assets: Asset[];
}

const PrintLabels: React.FC<PrintLabelsProps> = ({ assets }) => {
  const [isLoading, setIsLoading] = useState(false);

  const handleExportPDF = async () => {
    setIsLoading(true);
    try {
        // jsPDF is loaded from the script in index.html
        const { jsPDF } = (window as any).jspdf;
        const doc = new jsPDF('p', 'mm', 'a4');

        // A4 page is 210mm x 297mm
        const page_margin = 10;
        const label_width = 63.5;
        const label_height = 38.1;
        const labels_per_row = 3;
        const labels_per_col = 7;
        const horizontal_gap = (210 - (2 * page_margin) - (labels_per_row * label_width)) / (labels_per_row - 1);
        const vertical_gap = 2;
        
        // Load all images first for better performance
        const imagePromises = assets.map(asset => {
            return new Promise<{ img: HTMLImageElement, asset: Asset }>((resolve, reject) => {
                const img = new Image();
                img.crossOrigin = 'Anonymous';
                img.src = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(asset.qrCode)}`;
                img.onload = () => resolve({ img, asset });
                img.onerror = (err) => reject(new Error(`Failed to load image for ${asset.qrCode}: ${err}`));
            });
        });

        const loadedAssets = await Promise.all(imagePromises);

        let label_index = 0;
        for (const { img, asset } of loadedAssets) {
            if (label_index > 0 && label_index % (labels_per_row * labels_per_col) === 0) {
                doc.addPage();
            }

            const current_label_on_page = label_index % (labels_per_row * labels_per_col);
            const row = Math.floor(current_label_on_page / labels_per_row);
            const col = current_label_on_page % labels_per_row;

            const x = page_margin + col * (label_width + horizontal_gap);
            const y = page_margin + row * (label_height + vertical_gap);

            // QR Code image
            const qr_size = 25;
            const qr_x = x + (label_width - qr_size) / 2;
            const qr_y = y + 3;
            doc.addImage(img, 'PNG', qr_x, qr_y, qr_size, qr_size);
            
            // Asset Type Text
            doc.setFontSize(8);
            doc.setFont('helvetica', 'bold');
            const typeText = asset.type;
            doc.text(typeText, x + label_width / 2, qr_y + qr_size + 5, { align: 'center', maxWidth: label_width - 4 });

            // QR Code string
            doc.setFontSize(7);
            doc.setFont('helvetica', 'normal');
            const qrCodeText = asset.qrCode;
            doc.text(qrCodeText, x + label_width / 2, y + label_height - 3, { align: 'center' });

            label_index++;
        }

        doc.save('etiquetas_ativos_SGAITI.pdf');
    } catch (error) {
        console.error("Failed to generate PDF:", error);
        alert("Ocorreu um erro ao gerar o PDF. Verifique o console para mais detalhes.");
    } finally {
        setIsLoading(false);
    }
  };

  return (
    <div>
      <div className="no-print">
        <div className="flex justify-between items-center mb-6">
          <h1 className="text-3xl font-bold text-gray-800">Impressão de Etiquetas de QR Code</h1>
          <button
            onClick={handleExportPDF}
            disabled={isLoading}
            className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition flex items-center disabled:bg-blue-400 disabled:cursor-not-allowed"
          >
            {isLoading ? (
                <>
                    <svg className="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                        <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Gerando PDF...
                </>
            ) : (
                <>
                    <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Exportar Etiquetas para PDF
                </>
            )}
          </button>
        </div>
        <div className="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 mb-6" role="alert">
          <p className="font-bold">Instruções de Impressão</p>
          <p>Clique no botão 'Exportar' para gerar um arquivo PDF com todas as etiquetas dos ativos.</p>
          <ul className="list-disc list-inside mt-2 text-sm">
            <li>O arquivo será gerado no formato A4, ideal para impressão em folhas de etiquetas.</li>
            <li>Após o download, abra o PDF e imprima-o utilizando um leitor de PDF de sua preferência.</li>
            <li>Para melhores resultados, utilize a configuração de impressão "Tamanho Real" ou "100%".</li>
          </ul>
        </div>
      </div>

      <div id="label-sheet" className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        {assets.map(asset => (
          <div key={asset.id} className="label-item flex flex-col items-center justify-center p-4 border border-gray-300 rounded-lg text-center space-y-2 bg-white">
            <img
              src={`https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=${encodeURIComponent(asset.qrCode)}`}
              alt={`QR Code for ${asset.qrCode}`}
              className="w-24 h-24"
            />
            <p className="text-sm font-bold text-gray-800 break-words">{asset.type}</p>
            <p className="text-xs font-mono text-gray-600">{asset.qrCode}</p>
          </div>
        ))}
      </div>
    </div>
  );
};

export default PrintLabels;
