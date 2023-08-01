
interface FormErrorData {
  error?: string;
}

interface FormStatus {
  msg: string;
  isValid: boolean;
}

export const FormError = ({ error }: FormErrorData) => {
  if (!error) return null;
  return (
    <div className="text-red-600">{error}</div>
  )
}

export const FormStatus = ({ status }: { status: FormStatus | any }) => {
  if (!status) return null;
  return (
    <div
      className={`border px-4 py-3 my-4 rounded ${
        status.isValid
          ? "text-cyan-700 border-cyan-500 bg-cyan-200/50"
          : "text-red-700 border-red-400 bg-red-100"
      }`}
      role="alert"
    >
      {status.msg}
    </div>
  )
}
