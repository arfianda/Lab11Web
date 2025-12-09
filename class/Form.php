<?php

/**
 * Nama Class: Form
 * Deskripsi: Class untuk membuat form inputan dinamis (Text, Textarea, Select,
 * Radio, Checkbox)
 */
class Form
{
    private $fields = array();
    private $action;
    private $submit = "Submit Form";
    private $jumField = 0;

    public function __construct($action, $submit)
    {
        $this->action = $action;
        $this->submit = $submit;
    }

    public function displayForm()
    {
        echo "<form action='" . $this->action . "' method='POST'>";
        echo '<table width="100%" border="0">';

        foreach ($this->fields as $field) {
            echo "<tr><td align='right' valign='top'>" . $field['label'] . "</td>";
            echo "<td>";

            // Logika untuk menentukan tipe input
            switch ($field['type']) {
                case 'textarea':
                    echo "<textarea name='" . $field['name'] . "' cols='30' rows='4'></textarea>";
                    break;
                case 'select':
                    echo "<select name='" . $field['name'] . "'>";
                    foreach ($field['options'] as $value => $label) {
                        echo "<option value='" . $value . "'>" . $label . "</option>";
                    }
                    echo "</select>";
                    break;
                case 'radio':
                    foreach ($field['options'] as $value => $label) {
                        echo "<label><input type='radio' name='" . $field['name'] . "' value='" . $value . "'>" . $label . "</label> ";
                    }
                    break;
                case 'checkbox':
                    foreach ($field['options'] as $value => $label) {
                        // Untuk checkbox group, nama biasanya ditambah [] agar jadi array
                        echo "<label><input type='checkbox' name='" . $field['name'] . "[]' value='" . $value . "'>" . $label . "</label> ";
                    }
                    break;
                case 'password':
                    echo "<input type='password' name='" . $field['name'] . "'>";
                    break;
                default: // Defaultnya adalah text input biasa
                    echo "<input type='text' name='" . $field['name'] . "'>";
                    break;
            }
            echo "</td></tr>";
        }

        echo "<tr><td colspan='2'>";
        echo "<input type='submit' value='" . $this->submit . "'></td></tr>";
        echo "</table>";
        echo "</form>";
    }

    /**
     * addField
     * @param string $name Nama atribut (name="")
     * @param string $label Label untuk field
     * @param string $type Tipe input (text, textarea, select, radio, checkbox, password)
     * @param array $options Opsi untuk select/radio/checkbox (format: ['value' => 'Label'])
     */
    public function addField($name, $label, $type = "text", $options = array())
    {
        $this->fields[$this->jumField]['name'] = $name;
        $this->fields[$this->jumField]['label'] = $label;
        $this->fields[$this->jumField]['type'] = $type;
        $this->fields[$this->jumField]['options'] = $options;
        $this->jumField++;
    }
}
?>