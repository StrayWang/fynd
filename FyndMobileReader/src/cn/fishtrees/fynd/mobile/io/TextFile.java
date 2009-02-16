package cn.fishtrees.fynd.mobile.io;

import java.io.IOException;
import java.io.InputStreamReader;
import java.io.UnsupportedEncodingException;

import cn.fishtrees.fynd.mobile.ui.Console;

public class TextFile extends File {
	private String encoding = "";
	public String getEncoding() {
		return encoding;
	}
	public void setEncoding(String encoding) {
		this.encoding = encoding;
	}
	public TextFile(String fileFullPath){
		this(fileFullPath,"UTF-8");
	}
    public TextFile(String fileFullPath,String enc){
        super(fileFullPath);
        this.encoding = enc;
    }
    /**
     * 创建InputStreamReader用于按特定编码读取字符
     * @return InputStreamReader
     * @throws java.io.UnsupportedEncodingException
     * @throws java.io.IOException
     */
	public InputStreamReader createReader() throws UnsupportedEncodingException, IOException{
		return new InputStreamReader(this.openRead(),this.encoding);
	}
}
