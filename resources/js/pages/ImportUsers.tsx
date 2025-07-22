import React, { useState } from 'react';
import axios from 'axios';

type ImportResult = {
    summary: {
        total_rows: number;
        valid_rows: number;
        invalid_rows: number;
    };
    download_failed_url?: string;
};

export default function ImportUsers() {
    const [file, setFile] = useState<File | null>(null);
    const [result, setResult] = useState<ImportResult | null>(null);

    const handleUpload = async () => {
        if (!file) return alert("Select a file first!");

        const formData = new FormData();
        formData.append('file', file);

        try {
            const res = await axios.post<ImportResult>(
                'http://localhost:8000/api/import-users',
                formData,
                {
                    headers: { 'Content-Type': 'multipart/form-data' }
                }
            );

            setResult(res.data);
        } catch (error) {
            console.error("Upload failed:", error);
            alert("Upload failed. Check console.");
        }
    };

    return (
        <div className="p-6 max-w-xl mx-auto">
            <h2 className="text-xl mb-4 font-bold">User Excel Importer</h2>
            <input type="file" accept=".xlsx,.xls" onChange={e => setFile(e.target.files?.[0] || null)} />
            <button onClick={handleUpload} className="bg-blue-500 text-white px-4 py-2 rounded mt-2">Upload</button>

            {result && (
                <div className="mt-6">
                    <h3 className="text-lg font-semibold">Summary:</h3>
                    <p>Total Rows: {result.summary.total_rows}</p>
                    <p>Valid Rows: {result.summary.valid_rows}</p>
                    <p>Invalid Rows: {result.summary.invalid_rows}</p>

                    {result.download_failed_url && (
                        <a
                            href={result.download_failed_url}
                            className="text-blue-600 underline"
                            target="_blank"
                            rel="noreferrer"
                        >
                            Download Failed Rows
                        </a>
                    )}
                </div>
            )}
        </div>
    );
}
