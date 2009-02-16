/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package cn.fishtrees.fynd.mobile.ui;

import gr.fire.core.Component;
import gr.fire.core.Container;
import gr.fire.core.FireScreen;
import gr.fire.core.KeyListener;
import gr.fire.core.LayoutManager;
import gr.fire.core.Panel;
import gr.fire.core.PointerListener;
import gr.fire.ui.TextComponent;

import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.util.Vector;

import javax.microedition.lcdui.Canvas;
import javax.microedition.lcdui.Font;

import cn.fishtrees.fynd.mobile.MemoryManager;
import cn.fishtrees.fynd.mobile.io.File;
import cn.fishtrees.fynd.mobile.io.TextFile;

/**
 * 文件浏览器，读取UTF8格式的文本文件，以一页32KB分页显示，支持按键导航，触摸屏导航
 * 
 * @author fishtrees
 */
public class FileViewer extends Container implements KeyListener,
        PointerListener {

    /**
     * 默认分页大小（字节）
     */
    private static int PAGE_BYTE_SIZE = 8192;// 8KB
    private boolean isPageFirstDisplayed = true;
    /**
     * 文件完整路径
     */
    private String fileFullPath;
    /**
     * 总页数
     */
    private int pageCount;
    /**
     * 文件总长度（字节）
     */
    private long fileLength;
    /**
     * 当前页码
     */
    private int currentPageNo;
    /**
     * 封装的文件操作，目前主要用于读取文件
     */
    private TextFile file;
    /**
     * 文件字符编码，目前只支持UTF8格式 TODO:Must support multi-encoding
     */
    private String encoding = "UTF-8";
    /**
     * 用于显示文本的控件
     */
    private TextComponent txtCmp;
    /**
     * 屏幕指针按下时的X坐标，用于计算拖拽方向
     */
    private int pointerPressedX;
    /**
     * 屏幕指针按下时的Y坐标，用于计算拖拽方向
     */
    private int pointerPressedY;
    private Vector charPageCounts;
    /**
     *
     * @param manager
     *            布局管理器
     */
    public FileViewer(LayoutManager manager) {
        super(manager);
        this.setKeyListener(this);
        this.setPointerListener(this);
        this.charPageCounts = new Vector();
    }

    /**
     * @return the fileFullPath
     */
    public String getFileFullPath() {
        return fileFullPath;
    }

    /**
     * @param fileFullPath
     *            the fileFullPath to set
     */
    public void setFileFullPath(String fileFullPath) {
        this.fileFullPath = fileFullPath;
        TextFile f = new TextFile(fileFullPath, this.encoding);
        this.setFile(f);
    }

    /**
     * 显示上一页
     */
    public void moveToPrevPage() {
        final int currPageNo = this.currentPageNo;
        new Thread(new Runnable() {

            public void run() {
                displayPage(currPageNo - 1);
            }
        }).start();
    }

    /**
     * 显示下一页
     */
    public void moveToNextPage() {
        final int currPageNo = this.currentPageNo;
        new Thread(new Runnable() {

            public void run() {
                displayPage(currPageNo + 1);
            }
        }).start();
    }

    /**
     * Display "pageNum" parameter specified page
     *
     * @param pageNum
     */
    public synchronized void displayPage(int pageNum) {
        MemoryManager.releaseForce();
        Console.WriteLine("判断页码是否正确");
        if (pageNum <= 0) {
            pageNum = 1;
        }
        if (pageNum > this.pageCount) {
            pageNum = this.pageCount;
        }
        if(this.currentPageNo == pageNum){
            return;
        }
        InputStreamReader reader = null;

        try {
            Console.WriteLine("计算读取本页字符前应该跳过的字符");
            long skipCharCount = 0;
            for (int i = 0; i < this.charPageCounts.size(); i++) {
                if(null != this.charPageCounts.elementAt(i)){
                    skipCharCount = skipCharCount + ((Long)this.charPageCounts.elementAt(i)).longValue();
                }
            }
            Console.WriteLine("创建pageChars:Vector对象");
            Vector pageChars = null;
            if (this.pageCount > 1) {
                pageChars = new Vector(4096);
            } else {
                pageChars = new Vector();
            }
            Console.WriteLine("计算本页应该读取的字节长度");
            int bufferLength = PAGE_BYTE_SIZE;
            if (this.fileLength <= PAGE_BYTE_SIZE) {
                bufferLength = (int) this.fileLength;
            }
            Console.WriteLine("创建InputStreamReader");
            reader = this.file.createReader();
            Console.WriteLine("跳过"+skipCharCount+"个字符");
            reader.skip(skipCharCount);
            Console.WriteLine("读取字符");
            int ci = -1;
            int readedByteCount = 0;
            while ((ci = reader.read()) != -1) {
                if (readedByteCount >= bufferLength) {
                    break;
                }
                Character c = new Character((char) ci);
                readedByteCount += c.toString().getBytes(this.encoding).length;
                pageChars.addElement(c);
            }
            Console.WriteLine("记录每页显示的实际字符数");
            if(this.charPageCounts.size() > pageNum-1){
                this.charPageCounts.setElementAt(new Long(pageChars.size()), pageNum-1);
            } else {
                this.charPageCounts.addElement(new Long(pageChars.size()));
            }
            Console.WriteLine("将pageChars转换成String");
            char[] chars = new char[pageChars.size()];
            for (int i = 0; i < pageChars.size(); i++) {
                chars[i] = ((Character) pageChars.elementAt(i)).charValue();
            }
            String pageText = new String(chars);
            Console.WriteLine("显示当前页面");
            this.currentPageNo = pageNum;
            this.setPanelTitle();
            this.setTextContent(pageText);
            Console.WriteLine("FileViewer.displayPage : current page No. is '" + pageNum + "'");

        } catch (IOException ioe) {
            Console.WriteLine("IOException:", ioe);
        } catch (Exception e) {
            Console.WriteLine("Exception:", e);
        } finally {
            if (null != reader) {
                try {
                    reader.close();
                } catch (IOException ex) {
                    Console.WriteLine("Close InputStreamReader failed:", ex);
                }
            }
        }
    }

    public void keyPressed(int code, Component src) {
    }

    public void keyReleased(int code, Component src) {
        int gameAction = FireScreen.getScreen().getGameAction(code);
        switch (gameAction) {
            case Canvas.DOWN:
                this.scrollDown(false);
                break;

            case Canvas.LEFT:
                this.moveToNextPage();
                break;

            case Canvas.RIGHT:
                this.moveToPrevPage();
                break;

            case Canvas.UP:
                this.scrollUp(false);
                break;

        }


    }

    public void keyRepeated(int code, Component src) {
        // throw new UnsupportedOperationException("Not supported yet.");
    }

    public void pointerDragged(int x, int y, Component src) {
    }

    public void pointerPressed(int x, int y, Component src) {
        this.pointerPressedX = x;
        this.pointerPressedY = y;
    }

    public void pointerReleased(int x, int y, Component src) {
        int increaseX = x - this.pointerPressedX;
        int increaseY = y - this.pointerPressedY;
        int absIncreaseX = Math.abs(increaseX);
        int absIncreaseY = Math.abs(increaseY);

        if (absIncreaseX > absIncreaseY) {
            // pointer moved in horizontal direction
            if (increaseX > 0) {
                // pointer moved from left to right,we will display the previous
                // page.
                this.moveToPrevPage();
            } else if (increaseX < 0) {
                // pointer moved from right to left,we will show the next page.
                this.moveToNextPage();
            }

        } else if (absIncreaseX < absIncreaseY) {
            // pointer moved in vertical direction
            if (increaseY > 0) {
                this.scrollUp(false);
            } else if (increaseY < 0) {
                this.scrollDown(false);
            }

        }
    }

    /**
     * 代理方法，调用父组件scrollUp向上移动滚动条
     *
     * @param fast
     */
    protected void scrollUp(boolean fast) {
        if (this.parent != null && this.parent instanceof Panel) {
            ((Panel) this.parent).scrollUp(fast);
        }

    }

    /**
     * 代理方法，调用父组件scrollDown向下移动滚动条
     *
     * @param fast
     */
    protected void scrollDown(boolean fast) {
        if (this.parent != null && this.parent instanceof Panel) {
            ((Panel) this.parent).scrollDown(fast);
        }

    }

    /**
     * @return the file
     */
    public File getFile() {
        return file;
    }

    /**
     * @param file
     *            the file to set
     */
    public void setFile(TextFile file) {
        if (null != file) {
            this.file = file;
            this.fileLength = this.file.getLength();
            Console.WriteLine("File's length is " + this.fileLength);
            this.pageCount = (int) Math.ceil((double) this.fileLength / (double) PAGE_BYTE_SIZE);
            this.charPageCounts.setSize(this.pageCount);
            this.currentPageNo = 0;
            this.displayPage(1);
        }

    }

    /**
     * @return the text file's encoding
     */
    public String getEncoding() {
        return encoding;
    }

    /**
     * @param encoding
     *            the encoding to set
     */
    public void setEncoding(String encoding) {
        this.encoding = encoding;
    }

    /**
     * 将读取的文本显示到文本控件上
     *
     * @param text
     */
    protected void setTextContent(String text) {
        TextComponent newTextCmp = new TextComponent(text);
        newTextCmp.setFont(Font.getFont(Font.FACE_SYSTEM, Font.STYLE_PLAIN,
                Font.SIZE_SMALL));
        newTextCmp.validate();
        int textHeight = newTextCmp.getHeight();
        if (textHeight > this.height) {
            this.height = textHeight;
            this.setPrefSize(this.width, textHeight);
        }

        if (null != this.txtCmp) {
            this.remove(this.txtCmp);
            this.txtCmp = null;
        }

        this.txtCmp = newTextCmp;
        this.add(this.txtCmp);
        this.valid = false;
        this.validate();
        if (!this.isPageFirstDisplayed) {
            if (this.parent != null) {
                this.parent.repaint();
            } else {
                this.repaint();
            }

        }
        this.isPageFirstDisplayed = false;
        Console.WriteLine("Page text has been setted.");
    }

    public void cleanCurrentPage() {
        if (this.txtCmp != null) {
            this.remove(this.txtCmp);
        }

    }

    public int getCurrentPageNo() {
        return currentPageNo;
    }

    public int getPageCount() {
        return pageCount;
    }

    protected void setPanelTitle() {
        if (this.parent != null && this.parent instanceof Panel) {
            Panel p = (Panel) this.parent;
            p.setLabel(this.file.getFileName() + " " + this.currentPageNo + "/" + this.pageCount);
            Console.WriteLine("Panle's title has been setted!");
        }

    }

    public void setParent(Panel p) {
        this.parent = p;
    }
}
