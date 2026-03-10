package main

import (
	"archive/zip"
	"flag"
	"fmt"
	"io"
	"os"
	"path/filepath"
	"strings"
)

// Unzip 解压 zip 文件到指定目录
func Unzip(src, dest string) error {
	r, err := zip.OpenReader(src)
	if err != nil {
		return err
	}
	defer func(r *zip.ReadCloser) {
		err := r.Close()
		if err != nil {
			fmt.Println("关闭 zip 文件失败:", err)
		}
	}(r)

	for _, f := range r.File {
		fpath := filepath.Join(dest, f.Name)

		// 检查文件夹层级，防止目录遍历漏洞
		if !strings.HasPrefix(fpath, filepath.Clean(dest)+string(os.PathSeparator)) {
			return fmt.Errorf("非法文件路径: %s", fpath)
		}

		if f.FileInfo().IsDir() {
			// 创建文件夹
			if err := os.MkdirAll(fpath, os.ModePerm); err != nil {
				return err
			}
			continue
		}

		// 创建包含文件的父级目录
		if err := os.MkdirAll(filepath.Dir(fpath), os.ModePerm); err != nil {
			return err
		}

		// 打开目标文件
		outFile, err := os.OpenFile(fpath, os.O_WRONLY|os.O_CREATE|os.O_TRUNC, f.Mode())
		if err != nil {
			return err
		}
		defer func(outFile *os.File) {
			err := outFile.Close()
			if err != nil {
				fmt.Println("关闭目标文件失败:", err)
			}
		}(outFile)

		// 打开 zip 内的文件
		rc, err := f.Open()
		if err != nil {
			return err
		}
		defer func(rc io.ReadCloser) {
			err := rc.Close()
			if err != nil {
				fmt.Println("关闭 zip 文件失败:", err)
			}
		}(rc)

		// 将内容复制到目标文件
		if _, err := io.Copy(outFile, rc); err != nil {
			return err
		}
	}
	return nil
}

func main() {
	// 定义命令行参数
	src := flag.String("f", "", "源 ZIP 文件路径")
	dest := flag.String("d", "", "解压到的目标文件夹路径")

	// 解析命令行参数
	flag.Parse()

	// 检查参数是否提供
	if *src == "" || *dest == "" {
		fmt.Println("用法: go run main.go -f <压缩包路径> -d <解压路径>")
		return
	}

	err := Unzip(*src, *dest)
	if err != nil {
		fmt.Println("解压失败:", err)
	} else {
		fmt.Println("解压成功!")
	}
}
