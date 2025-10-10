import React from 'react';
import { InventoryRecord, MilitaryUser, Asset } from '../types';

interface InventoryDetailsModalProps {
  record: InventoryRecord;
  responsibleUser: MilitaryUser;
  users: MilitaryUser[];
  onClose: () => void;
  onReopenRequest: () => void;
}

const InventoryDetailsModal: React.FC<InventoryDetailsModalProps> = ({ record, responsibleUser, users, onClose, onReopenRequest }) => {
  
  const handleExportPDF = () => {
    const jsPDF = (window as any).jsPDF;
    const doc = new jsPDF();
    
    // Header
    doc.setFontSize(18);
    doc.text('Relatório de Inventário', 14, 22);
    
    // Metadata
    doc.setFontSize(11);
    doc.setTextColor(100);
    const completionDate = record.completionDate ? new Date(record.completionDate).toLocaleString('pt-BR') : 'Em Andamento';
    doc.text(`Data: ${completionDate}`, 14, 30);
    doc.text(`Responsável: ${responsibleUser.rank} ${responsibleUser.name}`, 14, 36);
    if(record.commissionNumber) {
        doc.text(`Comissão: ${record.commissionNumber}`, 14, 42);
    }

    // Summary table
    (doc as any).autoTable({
        startY: 50,
        head: [['Total de Itens', 'Conferidos', 'Faltantes', 'Não Catalogados']],
        body: [[
            record.summary.total,
            record.summary.found,
            record.summary.pending,
            record.summary.uncatalogued
        ]],
        theme: 'grid',
        headStyles: { fillColor: [22, 160, 133] }
    });

    let finalY = (doc as any).lastAutoTable.finalY || 60;

    if (record.observations) {
        doc.setFontSize(14);
        doc.text('Observações', 14, finalY + 12);
        doc.setFontSize(10);
        const splitObservations = doc.splitTextToSize(record.observations, 180);
        doc.text(splitObservations, 14, finalY + 18);
        finalY = finalY + 12 + (splitObservations.length * 5); // Approximate height
    }

    const generateTable = (title: string, columns: string[], data: (string|undefined)[][]) => {
        if (data.length === 0) return;
        doc.setFontSize(14);
        doc.text(title, 14, finalY + 12);
        (doc as any).autoTable({
            startY: finalY + 15,
            head: [columns],
            body: data,
            theme: 'striped'
        });
        finalY = (doc as any).lastAutoTable.finalY;
    }

    generateTable(
        `Itens Conferidos (${record.foundAssets.length})`,
        ['QR Code', 'Tipo/Modelo', 'Nº de Série'],
        record.foundAssets.map(a => [a.qrCode, a.type, a.serialNumber])
    );
    
    generateTable(
        `Itens Faltantes (${record.pendingAssets.length})`,
        ['QR Code', 'Tipo/Modelo', 'Nº de Série'],
        record.pendingAssets.map(a => [a.qrCode, a.type, a.serialNumber])
    );
    
    generateTable(
        `Itens Não Catalogados (${record.uncataloguedItems.length})`,
        ['QR Code Identificado'],
        record.uncataloguedItems.map(code => [code])
    );

    doc.save(`inventario_${record.id}_${new Date().toLocaleDateString('pt-BR').replace(/\//g, '-')}.pdf`);
  };

  const handleExportCSV = () => {
    const escapeCsvCell = (cell: string) => `"${cell.replace(/"/g, '""')}"`;

    const arrayToCsv = (header: string[], data: string[][]) => {
        const headerRow = header.map(escapeCsvCell).join(',');
        const dataRows = data.map(row => row.map(escapeCsvCell).join(','));
        return [headerRow, ...dataRows].join('\n');
    }

    let csvContent = "";
    csvContent += "Relatório de Inventário\n";
    const completionDate = record.completionDate ? new Date(record.completionDate).toLocaleString('pt-BR') : 'Em Andamento';
    csvContent += `Data,"${completionDate}"\n`;
    csvContent += `Responsável,"${responsibleUser.rank} ${responsibleUser.name}"\n`;
    if (record.commissionNumber) csvContent += `Comissão,"${record.commissionNumber}"\n`;
    csvContent += "\n";

    csvContent += "Resumo\n";
    csvContent += "Total,Conferidos,Faltantes,Não Catalogados\n";
    csvContent += `${record.summary.total},${record.summary.found},${record.summary.pending},${record.summary.uncatalogued}\n\n`;

    if (record.observations) {
        csvContent += `Observações\n`;
        csvContent += `"${record.observations.replace(/"/g, '""')}"\n\n`;
    }

    if (record.foundAssets.length > 0) {
        csvContent += `Itens Conferidos (${record.foundAssets.length})\n`;
        csvContent += arrayToCsv(['QR Code', 'Tipo/Modelo', 'Nº Série'], record.foundAssets.map(a => [a.qrCode, a.type, a.serialNumber])) + "\n\n";
    }
    if (record.pendingAssets.length > 0) {
        csvContent += `Itens Faltantes (${record.pendingAssets.length})\n`;
        csvContent += arrayToCsv(['QR Code', 'Tipo/Modelo', 'Nº Série'], record.pendingAssets.map(a => [a.qrCode, a.type, a.serialNumber])) + "\n\n";
    }
    if (record.uncataloguedItems.length > 0) {
        csvContent += `Itens Não Catalogados (${record.uncataloguedItems.length})\n`;
        csvContent += arrayToCsv(['QR Code Identificado'], record.uncataloguedItems.map(code => [code])) + "\n\n";
    }
    
    const blob = new Blob(["\uFEFF" + csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement("a");
    const url = URL.createObjectURL(blob);
    link.setAttribute("href", url);
    link.setAttribute("download", `inventario_${record.id}_${new Date().toLocaleDateString('pt-BR').replace(/\//g, '-')}.csv`);
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  };

  const renderList = (title: string, items: (Asset | string)[]) => (
    <div>
        <h4 className="text-lg font-semibold text-gray-700 mb-2 border-b pb-1">{title}</h4>
        {items.length > 0 ? (
            <ul className="divide-y divide-gray-200 max-h-40 overflow-y-auto pr-2">
                {items.map((item, index) => (
                    <li key={index} className="py-2 text-sm">
                        {typeof item === 'string' ? item : `${item.qrCode} - ${item.type}`}
                    </li>
                ))}
            </ul>
        ) : <p className="text-sm text-gray-500 italic">Nenhum item.</p>}
    </div>
  );

  const isReopened = record.status === 'Reaberto';

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50" onClick={onClose}>
      <div className="bg-white p-8 rounded-lg shadow-2xl w-full max-w-5xl max-h-[90vh] flex flex-col" onClick={e => e.stopPropagation()}>
        <div className="flex justify-between items-start mb-4">
          <div>
            <h2 className="text-2xl font-bold text-gray-800">Relatório de Inventário</h2>
            <p className="text-gray-600">Concluído em: {record.completionDate ? new Date(record.completionDate).toLocaleString('pt-BR') : 'N/A'}</p>
          </div>
          <button onClick={onClose} className="-mt-2 -mr-2 text-gray-500 hover:text-gray-800" aria-label="Fechar">
            <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"></path></svg>
          </button>
        </div>
        
        <div className="text-sm grid grid-cols-2 gap-x-4 gap-y-1 mb-6">
            <div><strong>Responsável:</strong> {responsibleUser.rank} {responsibleUser.name}</div>
            <div><strong>Comissão:</strong> {record.commissionNumber || 'N/A'}</div>
            <div><strong>Status:</strong> <span className={`px-2 py-1 text-xs font-medium rounded-full ${isReopened ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800'}`}>{record.status}</span></div>
            <div className="col-span-2 pt-2">
                <div className="flex space-x-4 text-center">
                    <div className="flex-1 bg-gray-100 p-2 rounded"><strong>Total:</strong> {record.summary.total}</div>
                    <div className="flex-1 bg-green-100 p-2 rounded"><strong>Conferidos:</strong> {record.summary.found}</div>
                    <div className="flex-1 bg-red-100 p-2 rounded"><strong>Faltantes:</strong> {record.summary.pending}</div>
                    <div className="flex-1 bg-yellow-100 p-2 rounded"><strong>Não Catalogados:</strong> {record.summary.uncatalogued}</div>
                </div>
            </div>
        </div>

        <div className="flex-grow overflow-y-auto space-y-6 pr-2">
          {record.observations && (
              <div className="p-4 bg-gray-50 rounded-lg border">
                <h4 className="font-semibold text-gray-800">Observações Registradas:</h4>
                <p className="text-sm text-gray-600 whitespace-pre-wrap mt-1">{record.observations}</p>
              </div>
          )}

          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            {renderList(`Conferidos (${record.foundAssets.length})`, record.foundAssets)}
            {renderList(`Faltantes (${record.pendingAssets.length})`, record.pendingAssets)}
            {renderList(`Não Catalogados (${record.uncataloguedItems.length})`, record.uncataloguedItems)}
          </div>

          {record.reopenHistory && record.reopenHistory.length > 0 && (
              <div className="mt-4 pt-4 border-t">
                  <h4 className="text-lg font-semibold text-gray-700 mb-2">Histórico de Reabertura</h4>
                  <div className="max-h-24 overflow-y-auto space-y-2 pr-2">
                      {record.reopenHistory.slice().reverse().map((item, index) => {
                          const user = users.find(u => u.id === item.reopenedByUserId);
                          return (
                              <div key={index} className="bg-yellow-50 p-2 rounded-md text-sm">
                                  <p><strong>Data:</strong> {new Date(item.reopenDate).toLocaleString('pt-BR')}</p>
                                  <p><strong>Usuário:</strong> {user ? `${user.rank} ${user.name}` : 'N/A'}</p>
                                  <p><strong>Justificativa:</strong> {item.justification}</p>
                              </div>
                          )
                      })}
                  </div>
              </div>
          )}
        </div>

        <div className="mt-6 pt-4 border-t flex justify-between items-center">
            <div className="space-x-2 flex items-center">
                <button onClick={handleExportPDF} className="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition text-sm flex items-center">
                    <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Exportar PDF
                </button>
                 <button onClick={handleExportCSV} className="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition text-sm flex items-center">
                    <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Exportar Excel (.csv)
                </button>
                 <button onClick={onReopenRequest} disabled={isReopened} className="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition text-sm flex items-center disabled:bg-gray-400 disabled:cursor-not-allowed">
                    <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 4v5h5M20 20v-5h-5M4 20h5v-5M20 4h-5v5"></path></svg>
                    Reabrir Inventário
                </button>
            </div>
            <button onClick={onClose} className="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">Fechar</button>
        </div>
      </div>
    </div>
  );
};

export default InventoryDetailsModal;