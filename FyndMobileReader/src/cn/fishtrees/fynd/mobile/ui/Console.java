package cn.fishtrees.fynd.mobile.ui;

import gr.fire.core.FireScreen;
import gr.fire.util.Logger;

import javax.microedition.lcdui.Command;
import javax.microedition.lcdui.CommandListener;
import javax.microedition.lcdui.Displayable;
import javax.microedition.lcdui.Form;

public class Console extends Form implements CommandListener, Logger {
	private static final int SIZE = 3000;
	private StringBuffer buf = new StringBuffer(SIZE);
	private Command clear, back;

	private static Console instance = null;

	protected Console() {
		// super("Console","",SIZE+10,TextField.ANY);
		super("Console");
		clear = new Command("Clear", Command.OK, 1);
		back = new Command("Back", Command.BACK, 1);

		addCommand(back);
		addCommand(clear);
		setCommandListener(this);
	}

	public void println(String txt) {
		buf.append(txt);
		buf.append('\n');
		if (buf.length() > SIZE)
			buf.delete(0, buf.length() - SIZE);
		super.deleteAll();
		super.append(buf.toString());
        System.out.println(txt);
	}

	public void println(String txt, Exception e) {
		this.println(txt);
		this.println(e.toString());
        e.printStackTrace();
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see
	 * javax.microedition.lcdui.CommandListener#commandAction(javax.microedition
	 * .lcdui.Command, javax.microedition.lcdui.Displayable)
	 */
	public void commandAction(Command c, Displayable arg1) {
		if (c == clear) {
			buf.delete(0, buf.length());
			super.deleteAll();
		} else {
			FireScreen screen = FireScreen.getScreen();
			screen.setCurrent(screen.getCurrent());
		}
	}

	public static Console getInstance() {
		if (null == instance) {
			instance = new Console();
		}
		return instance;
	}

	public static void WriteLine(String txt) {
		getInstance().println(txt);
	}

	public static void WriteLine(String txt, Exception e) {
		getInstance().println(txt, e);
	}
}
