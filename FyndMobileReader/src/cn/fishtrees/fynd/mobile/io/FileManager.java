/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package cn.fishtrees.fynd.mobile.io;

import gr.fire.util.StringUtil;

import java.io.IOException;
import java.util.Enumeration;
import java.util.Stack;
import java.util.Vector;

import javax.microedition.io.Connector;
import javax.microedition.io.file.FileConnection;
import javax.microedition.io.file.FileSystemRegistry;

/**
 * 
 * @author fishtrees
 */
public class FileManager {

	/**
	 * 父目录字符串
	 */
	public static final String UP_DIRECTORY = "..";
	/**
	 * 根目录字符串
	 */
	public static final String MEGA_ROOT = "/";
	/**
	 * FC规范要求的路径分隔符，String类型
	 */
	public static final String SEP_STR = "/";
	/**
	 * FC规范要求的路径分隔符，char类型
	 */
	public static final char SEP = '/';
	private String currentDir;

	private Stack dirStack;

	public FileManager() {
		this(FileManager.MEGA_ROOT);
	}

	public FileManager(String path) {
		this.dirStack = new Stack();
		this.initDirStack(path);
	}

	/**
	 * 初始化目录栈
	 * 
	 * @param path
	 *            以"/"分隔的路径，每个路径将被压入目录栈
	 */
	protected void initDirStack(String path) {
		Vector dirs = StringUtil.split(path, SEP_STR);
		this.dirStack.removeAllElements();
		for (int i = 0; i < dirs.size(); i++) {
			String dir = ((String) dirs.elementAt(i)).trim();
			if (dir.equals("") || dir.indexOf('.') > 0) {
				continue;
			}
			this.dirStack.push(dir);
		}
		if (this.dirStack.empty()) {
			this.currentDir = MEGA_ROOT;
		} else {
			this.currentDir = (String) this.dirStack.peek();
		}
		//Log.logInfo("The current directory is '" + this.currentDir + "'");
	}

	/**
	 * 获取当前目录
	 * 
	 * @return
	 */
	public String getCurrentDir() {
		return this.currentDir;
	}

	public String getFullPath() {
		String url = "/";
		for (int i = 0; i < this.dirStack.size(); i++) {
			String dirName = (String) this.dirStack.elementAt(i);
			if (dirName.equals("")) {
				continue;
			}
			//Log.logInfo("FileManager.getFullPath:dirName is '" + dirName + "'");
			url = url + dirName + "/";
		}
		return url;
	}

	/**
	 * 转到指定的路径，重新初始化目录栈
	 * 
	 * @param path
	 */
	public void switchToPath(String path) {
		this.initDirStack(path);
	}

	/**
	 * 改变当前目录
	 * 
	 * @param dirName
	 *            要进入的目录，只接受目录名，不接受包含"/"的路径
	 */
	public void changeDir(String dirName) {
		if (dirName.indexOf(FileManager.SEP_STR) > 0
				&& dirName.indexOf(FileManager.SEP_STR) < (dirName.length() - 1)) {
			throw new java.lang.IllegalArgumentException(
					"Illegal argument,maybe you can try switchToPath method");
		}
		if (dirName.indexOf(FileManager.SEP_STR) == 0
				|| dirName.indexOf(FileManager.SEP_STR) == (dirName.length() - 1)) {
			dirName = dirName.replace(SEP, (char) 0).trim();
		}
		if (dirName.equals(UP_DIRECTORY)) {
			this.dirStack.pop();
		} else {
			this.dirStack.push(dirName);
		}
		if (this.dirStack.empty()) {
			this.currentDir = MEGA_ROOT;
		} else {
			this.currentDir = (String) this.dirStack.peek();
		}
	}

	public Enumeration listDirectory(String path) throws IOException {
		this.switchToPath(path);
		return this.listDirectory();
	}

	public Enumeration listDirectory() throws IOException {
		Enumeration en;
		FileConnection fc = null;
		
		//Log.logInfo("FileManager.listDirectory : current directory is '" + this.currentDir + "'");
		try {
			if (MEGA_ROOT.equals(this.currentDir)) {
				en = FileSystemRegistry.listRoots();
			} else {
				String url = "file://localhost" + this.getFullPath();
				//Log.logInfo("FileManager.listDirectory:url is '" + url + "'");
				fc = (FileConnection) Connector.open(url,Connector.READ);
				en = fc.list();
			}

		} catch (IOException ioe) {
			throw ioe;
		} finally {
			if (null != fc) {
				fc.close();
			}
		}
		return en;
	}

	/**
	 * 获取路径中的文件名
	 * 
	 * @param fullPath
	 *            一个合法的路径，如/foo/bar.txt，/foo/bar1/bar2/
	 * @return 文件名
	 */
	public static String getFileNameFromPath(String fullPath) {
		int lastSlashPos = fullPath.lastIndexOf('/');
		String fileName = "";
		if (lastSlashPos < 0) {
			fileName = fullPath;
		} else {
			fileName = fullPath.substring(lastSlashPos + 1);
		}
		return fileName;
	}

	/**
	 * 获取文件扩展名
	 * 
	 * @param fileName
	 *            带路径的文件名或不带路径的文件名
	 * @return 扩展名，不带点号
	 */
	public static String getExtensionName(String fileName) {
		int lastDotPos = fileName.lastIndexOf('.');
		String extName = "";
		if (lastDotPos >= 0) {
			extName = fileName.substring(lastDotPos + 1);
		}
		return extName;
	}
}
