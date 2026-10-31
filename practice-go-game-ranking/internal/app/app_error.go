package app

import "fmt"

// アプリ固有のエラーを表現
type AppError struct {
	Errors         []error
	HttpStatusCode int
	Message        string
}

func NewAppError(err error, httpStatusCode int, message string) *AppError {
	return &AppError{
		Errors:         []error{err},
		HttpStatusCode: httpStatusCode,
		Message:        message,
	}
}

func (e *AppError) Error() string {
	return fmt.Sprintf("HttpStatusCode: %d, message: %s", e.HttpStatusCode, e.Message)
}

func (e *AppError) WithStack(err error) *AppError {
	e.Errors = append(e.Errors, err)
	return e
}
